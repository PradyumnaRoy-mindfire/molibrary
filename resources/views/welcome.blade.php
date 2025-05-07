<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <title>Home</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick-theme.min.css">
  <link rel="stylesheet" href="{{ url('css/welcome.css') }}">
</head>

<body>
  <h1> MoLibrary </h1>
  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="#">MoLibrary</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">About Us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">Register</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{  route('login') }}">Sign-in</a>
      </li>
    </ul>
    <div class="nav-searchbar">
      <i class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
        </svg>
      </i>
      <input type="search" name="search" id="search" placeholder="Search Books">
    </div>
  </nav>
  <h2>Need A Book? We Got Your Shelf!</h2>
  <div id="imgdiv">
    <div id="imgdiv2">
      <div id="imgdiv3">
        <img class="img2" src="https://previews.123rf.com/images/dolgachov/dolgachov1904/dolgachov190400052/119868258-high-school-student-girl-reading-book-at-library.jpg" alt="Book" width="685" height="500">
      </div>
      <div id="imgdiv4">
        <div id="imgdiv5">
          <h4> Innovative Learning</h4>
          <p> Wondering for books? We at Library Genesis are with you. Explore amazing collection of our books to learn and grow. Register yourself today and gain access to our collection made just for you.</p>
        </div>
      </div>
    </div>


  </div>
  <div class="wrapper">
    <div id="leftmost">Popular Books Among Readers</div>
    <div class="carousel">
      <div>
        <img class="img1" style="clear:left;" src="https://images-na.ssl-images-amazon.com/images/I/91fJxgs69QL.jpg" alt="Book" width="200" height="300">
      </div>

      <div><img class="img1" src="https://m.media-amazon.com/images/I/51AHZGhzZEL.jpg" alt="Book" width="200" height="300"></div>

      <div><img class="img1" src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1470082995i/29056083.jpg" alt="Book" width="200" height="300"></div>

      <div><img class="img1" src="https://m.media-amazon.com/images/G/01/prime/primeinsider2/prnewesttitles/nov18/watching_you._CB480611966_.jpg" alt="Book" width="200" height="300"></div>

      <div><img class="img1" src="https://www.creatopy.com/blog/wp-content/uploads/2020/08/Jaws-Book-Cover-400x600.jpg" alt="Book" width="200" height="300"></div>
      <div><img class="img1" src="https://www.gannett-cdn.com/-mm-/3ce393aec430712acd545d8979b88674af8b6fa5/c=0-147-1605-2287/local/-/media/2018/04/13/USATODAY/USATODAY/636592352838445588-AP-Obit-Harper-Lee.1.jpg" alt="Book" width="200" height="300"></div>
      <div><img class="img1" src="https://img.buzzfeed.com/buzzfeed-static/static/2021-01/15/4/asset/0308066dc00f/sub-buzz-11116-1610686502-8.jpg" alt="Book" width="200" height="300"></div>
    </div>
  </div>
  <div class="main">
    <h2>Events</h2>
    <ul class="cards">
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://www.a2ztranscripts.com/img/srv3.jpg" style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Fiction Books</h2>
            <p class="card_text">the best new fiction of 2022, from gripping sequels to incredible debuts from fresh new voices. We also look back at the best fiction books of 2021.</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://images.pexels.com/photos/694740/pexels-photo-694740.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500" style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Non Fiction Books</h2>
            <p class="card_text">From eye-opening autobiographies to political exposés, broaden your horizons with our edit of the best non-fiction books of all time.</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://www.finsmes.com/wp-content/uploads/2018/04/pen.jpg" style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Finance Books</h2>
            <p class="card_text">Personal finance books help you manage your money better. We researched the best options for you, from books on debt management to budgeting.</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://picsum.photos/500/300/?image=14 " style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Young Adults</h2>
            <p class="card_text">The books we read in our teen years often become our favorites.Find the best book here for you.Chekcout first ,you will love it</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://lumaworld.in/cdn/shop/files/sci-fi-1_1024x1024.jpg?v=1705485643" style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Sci-Fi Books</h2>
            <p class="card_text">Want to be in a world of imaginative and futuristic conecpts such as advanced technology? This place is just for you!</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
      <li class="cards_item">
        <div class="card">
          <div class="card_image"><img src="https://i.guim.co.uk/img/media/77e3e93d6571da3a5d77f74be57e618d5d930430/0_0_2560_1536/500.jpg?quality=85&auto=format&fit=max&s=79cd358df7f1b6ee1a16610f000e9f78" style="height: 300px; width:500px"></div>
          <div class="card_content">
            <h2 class="card_title">Top 10 Books</h2>
            <p class="card_text">Don't have to search for right rated books anymore.We have combined top 10 books for you, rated by you!</p>
            <a class="btn card_btn" href="{{ route('login') }}">Explore</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div id="bottombef">
    <div class="container">
      <h2>Explore More</h2>
      <div id="accordion">
        <div class="card">
          <div class="card-header">
            <a class="card-link" data-toggle="collapse" href="#collapseOne">
              Special Programs
            </a>
          </div>
          <div id="collapseOne" class="collapse show" data-parent="#accordion">
            <div class="card-body">
              MoLibrary can be your guide to evolve more with events like essay writing, poetry writing, debate competitions and many more. We conduct Fiction factory flash fiction for all your unpublished stories. You can participate and win many cash prizes and awards. Stay connected for the updates!
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
              Research and Development
            </a>
          </div>
          <div id="collapseTwo" class="collapse" data-parent="#accordion">
            <div class="card-body">
              We welcome you to take advanatge of all our world-class available resources to provide you competitive advantage at the business, industry, or national level.
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
              Our Team
            </a>
          </div>
          <div id="collapseThree" class="collapse" data-parent="#accordion">
            <div class="card-body">
              MoLibrary is maintained and co-ordinated by constant hardwork and dediaction of our team members, moderators and founders.If you are looking for self role in our team mail us with your description.
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>


  <div id="bottom"> &#169 Copyright 2025 MoLibrary</div>

</body>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.js"></script>

<script>
  $(document).ready(function() {
    $('.carousel').slick({
      slidesToShow: 3,
      dots: true,
      centerMode: true,
    });
  });

  function showabout() {

  }
</script>