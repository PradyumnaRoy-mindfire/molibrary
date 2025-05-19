let pdfDoc = null;
let flipbook = null;
let isFullscreen = false;
let fullscreenScale = 0; 
const defaultWidth = 1200; 
const defaultHeight = 840;
// let startPage = 2;

function loadPDF() {
    console.log("Loading PDF from URL:", url);
    $('#loading').show(); 
    
    pdfjsLib.getDocument({
        url: url,
        withCredentials: true,
        cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/cmaps/',
        cMapPacked: true
    }).promise.then(function (pdf) {
        pdfDoc = pdf;
        const total = pdf.numPages;
        flipbook = $('#flipbook');
        let pagesRendered = 0;
        
        for (let i = 1; i <= total; i++) {
            const pageDiv = $('<div class="page"></div>');
            const canvas = $('<canvas></canvas>')[0];
            pageDiv.append(canvas);
            flipbook.append(pageDiv);
            
            renderPage(i, canvas).then(() => {
                pagesRendered++;
                
                if (pagesRendered === total) {
                    $('#loading').hide(); 
                    initializeTurn();
                    
                    $('#controls').fadeIn();
                    
                    positionControls();
                }
            }).catch(error => {
                console.error("Error rendering page " + i + ":", error);
                pagesRendered++;
                
                if (pagesRendered === total) {
                    $('#loading').hide();
                    initializeTurn();
                    $('#controls').fadeIn();
                    positionControls();
                }
            });
        }
    }).catch(function(error) {
        console.error("Error loading PDF:", error);
        $('#loading').hide();
        $('#flipbook').html('<div class="error-message">Failed to load PDF. Please try again later.</div>');
    });
}

function initializeTurn() {
    const viewportWidth = isFullscreen ? window.innerWidth : defaultWidth;
    const viewportHeight = isFullscreen ? window.innerHeight : defaultHeight;
    
    flipbook.turn({
        width: viewportWidth,
        height: viewportHeight,
        autoCenter: true,
        display: 'double',
        acceleration: true,
        gradients: true,
        elevation: 50,
        page:startPage,
        when: {
            turning: function(e, page, view) {
                $('#page-num').text(page);
                
                playPageTurnSound();

                if (page === 1 || e.originalEvent) {
                    sendReadingProgress(page);
                }
            }
        }
    });
    
    $('#total-pages').text(flipbook.turn('pages'));
    positionControls();
}

function playPageTurnSound() {
    const audio = new Audio();
    audio.volume = 0.2;
    audio.src = turn_page;
    audio.play();
}

    // Sending reading progress
function sendReadingProgress(currentPage) {
    if (!pdfDoc) return;

    const totalPages = pdfDoc.numPages;
    const progress = Math.round((currentPage / totalPages) * 100).toFixed(1);

    // Send via AJAX
    $.ajax({
        url: progress_url,
        method: 'POST',
        data: {
            page: currentPage,
            progress: progress,
            _token: csrf
        },
        success: function(response) {
            console.log("Progress sent:", progress + "%");
        },
        error: function(error) {
            console.error("Failed to send progress:", error);
        }
    });
}


function renderPage(pageNum, canvas) {
    return new Promise((resolve, reject) => {
        pdfDoc.getPage(pageNum).then(page => {
            const originalViewport = page.getViewport({ scale: 1.0 });
            
            const scaleX = (defaultWidth / 2) / originalViewport.width;
            const scaleY = defaultHeight / originalViewport.height;
            const scale = Math.min(scaleX, scaleY) * 1.5;
            
            const pixelRatio = window.devicePixelRatio || 1;
            const viewport = page.getViewport({ scale: scale * pixelRatio });
            
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            canvas.style.width = (viewport.width / pixelRatio) + 'px';
            canvas.style.height = (viewport.height / pixelRatio) + 'px';
            
            const ctx = canvas.getContext('2d');
            
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            
            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
                renderInteractiveForms: true,
                enableWebGL: true
            };
            
            page.render(renderContext)
                .promise.then(() => {
                    resolve();
                })
                .catch(err => {
                    console.error('Error rendering page ' + pageNum + ':', err);
                    reject(err);
                });
        }).catch(err => {
            console.error('Error getting page ' + pageNum + ':', err);
            reject(err);
        });
    });
}

function toggleFullscreen() {
    const flipbookContainer = document.getElementById('flipbook-container');
    
    if (!isFullscreen) {
        // Enter fullscreen
        if (flipbookContainer.requestFullscreen) {
            flipbookContainer.requestFullscreen();
        } else if (flipbookContainer.webkitRequestFullscreen) {
            flipbookContainer.webkitRequestFullscreen();
        } else if (flipbookContainer.msRequestFullscreen) {
            flipbookContainer.msRequestFullscreen();
        }
    } else {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

// Handle fullscreen change events
function handleFullscreenChange() {
    isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
    
    if (isFullscreen) {
        $('#fullscreen-icon').removeClass('fa-expand').addClass('fa-compress');
        $('#flipbook-container').addClass('fullscreen');
        
        // Adjust flipbook size for fullscreen
        setTimeout(() => {
            flipbook.turn('size', window.innerWidth - 20, window.innerHeight - 100);
            positionControls();
        }, 300);
    } else {
        $('#fullscreen-icon').removeClass('fa-compress').addClass('fa-expand');
        $('#flipbook-container').removeClass('fullscreen');
        
        // Reset to default size
        setTimeout(() => {
            flipbook.turn('size', defaultWidth, defaultHeight);
            positionControls();
        }, 300);
    }
}

// Position the navigation controls
function positionControls() {
    if (!flipbook || !flipbook.data('turn')) return;
    
    $('.nav-prev, .nav-next').show();
    
    if (isFullscreen) {
        $('.nav-prev').css({
            left: '20px',
            top: '50%',
            transform: 'translateY(-50%)',
            zIndex: 2000
        });
        
        $('.nav-next').css({
            right: '20px',
            top: '50%',
            transform: 'translateY(-50%)',
            zIndex: 2000
        });
        
        $('#bottom-controls').css({
            position: 'fixed',
            bottom: '20px',
            left: '50%',
            transform: 'translateX(-50%)',
            zIndex: 2000,
            padding: '15px 30px',
            backgroundColor: 'rgba(255, 255, 255, 0.9)',
            boxShadow: '0 2px 10px rgba(0, 0, 0, 0.3)'
        });
    } else {
        $('.nav-prev').css({
            left: '-60px',
            top: '50%',
            transform: 'translateY(-50%)'
        });
        
        $('.nav-next').css({
            right: '-60px',
            top: '50%',
            transform: 'translateY(-50%)'
        });
        
        $('#bottom-controls').css({
            position: 'absolute',
            bottom: '-55px',
            left: '50%',
            transform: 'translateX(-50%)',
            padding: '10px 20px',
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            boxShadow: '0 2px 10px rgba(0, 0, 0, 0.1)'
        });
    }
}

$(document).ready(function() {
    loadPDF();
    
    $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', handleFullscreenChange);
    
    $(window).resize(function() {
        if (isFullscreen && flipbook && flipbook.data('turn')) {
            flipbook.turn('size', window.innerWidth - 20, window.innerHeight - 100);
            positionControls();
        }
    });
    
    $('.nav-prev').click(function() {
        flipbook.turn('previous');
    });
    
    $('.nav-next').click(function() {
        flipbook.turn('next');
        const currentPage = flipbook.turn('page') + 1;
        sendReadingProgress(currentPage);
    });
    
    $(document).keydown(function(e) {
        switch(e.which) {
            case 37: // left arrow
                flipbook.turn('previous');
                e.preventDefault();
                break;
            case 39: // right arrow
                flipbook.turn('next');
                e.preventDefault();
                break;
        }
    });
    
    $('#controls').hide();
    
    positionControls();
});