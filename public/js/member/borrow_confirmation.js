document.addEventListener('DOMContentLoaded', function() {
    const descriptionText = document.getElementById('description-text');
    const readMoreBtn = document.getElementById('read-more-btn');
    
    function checkDescriptionLength() {
        const isOverflowing = descriptionText.scrollHeight > descriptionText.clientHeight;
        
        if (!isOverflowing) {
            // If content fits, hide the button and fade
            readMoreBtn.style.display = 'none';
            descriptionText.classList.remove('collapsed-description');
        } else {
            readMoreBtn.style.display = 'inline-block';
        }
    }
    
    readMoreBtn.addEventListener('click', function() {
        if (descriptionText.classList.contains('collapsed-description')) {
            // Expand
            descriptionText.classList.remove('collapsed-description');
            readMoreBtn.textContent = 'See less';
        } else {
            // Collapse
            descriptionText.classList.add('collapsed-description');
            readMoreBtn.textContent = 'See more';
        }
    });
    
    // Initial check
    checkDescriptionLength();
    
    window.addEventListener('resize', checkDescriptionLength);

    
});



