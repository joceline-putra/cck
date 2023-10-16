		<main class="main">
			<nav aria-label="breadcrumb" class="breadcrumb-nav">
				<div class="container">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="demo4.html"><i class="icon-home"></i></a></li>
						<li class="breadcrumb-item active" aria-current="page">Blog Post</li>
					</ol>
				</div><!-- End .container -->
			</nav>

			<div class="container">
				<div class="row">
					<div class="col-lg-9">
						<article class="post single">
							<div class="post-media">
								<img src="<?php echo $asset; ?>assets/images/blog/post-1.jpg" alt="Post">
							</div><!-- End .post-media -->

							<div class="post-body">
								<div class="post-date">
									<span class="day">22</span>
									<span class="month">Jun</span>
								</div><!-- End .post-date -->

								<h2 class="post-title">Top New Collection</h2>

								<div class="post-meta">
									<a href="#" class="hash-scroll">0 Comments</a>
								</div><!-- End .post-meta -->

								<div class="post-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras non placerat mi.
										Etiam non tellus sem. Aenean pretium convallis lorem, sit amet dapibus ante
										mollis a. Integer bibendum interdum sem, eget volutpat purus pulvinar in. Sed
										tristique augue vitae sagittis porta. Phasellus ullamcorper, dolor suscipit
										mattis viverra, sapien elit condimentum odio, ut imperdiet nisi risus sit amet
										ante. Sed sem lorem, laoreet et facilisis quis, tincidunt non lorem. Etiam
										tempus, dolor in sollicitudin faucibus, sem massa accumsan erat.
									</p>

									<h3>“ Many
										desktop publishing packages and web page editors now use Lorem Ipsum as their
										default model search for evolved over sometimes by accident, sometimes on
										purpose ”
									</h3>

									<p>Aenean lorem diam, venenatis nec venenatis id, adipiscing ac massa. Nam vel dui
										eget justo dictum pretium a rhoncus ipsum. Donec venenatis erat tincidunt nunc
										suscipit, sit amet bibendum lacus posuere. Sed scelerisque, dolor a pharetra
										sodales, mi augue consequat sapien, et interdum tellus leo et nunc. Nunc
										imperdiet eu libero ut imperdiet.
									</p>
								</div><!-- End .post-content -->

								<div class="post-share">
									<h3 class="d-flex align-items-center">
										<i class="fas fa-share"></i>
										Share this post
									</h3>

									<div class="social-icons">
										<a href="#" class="social-icon social-facebook" target="_blank"
											title="Facebook">
											<i class="icon-facebook"></i>
										</a>
										<a href="#" class="social-icon social-twitter" target="_blank" title="Twitter">
											<i class="icon-twitter"></i>
										</a>
										<a href="#" class="social-icon social-linkedin" target="_blank"
											title="Linkedin">
											<i class="fab fa-linkedin-in"></i>
										</a>
										<a href="#" class="social-icon social-gplus" target="_blank" title="Google +">
											<i class="fab fa-google-plus-g"></i>
										</a>
										<a href="#" class="social-icon social-mail" target="_blank" title="Email">
											<i class="icon-mail-alt"></i>
										</a>
									</div><!-- End .social-icons -->
								</div><!-- End .post-share -->

								<div class="post-author">
									<h3><i class="far fa-user"></i>Author</h3>

									<figure>
										<a href="#">
											<img src="<?php echo $asset; ?>assets/images/blog/author.jpg" alt="author">
										</a>
									</figure>

									<div class="author-content">
										<h4><a href="#">John Doe</a></h4>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod
											odio, gravida pellentesque urna varius vitae. Sed dui lorem, adipiscing in
											adipiscing et, interdum nec metus. Mauris ultricies, justo eu convallis
											placerat, felis enim ornare nisi, vitae mattis nulla ante id dui.</p>
									</div><!-- End .author.content -->
								</div><!-- End .post-author -->

								<div class="comment-respond">
									<h3>Leave a Reply</h3>

									<form action="#">
										<p>Your email address will not be published. Required fields are marked *</p>

										<div class="form-group">
											<label>Comment</label>
											<textarea cols="30" rows="1" class="form-control" required></textarea>
										</div><!-- End .form-group -->

										<div class="form-group">
											<label>Name</label>
											<input type="text" class="form-control" required>
										</div><!-- End .form-group -->

										<div class="form-group">
											<label>Email</label>
											<input type="email" class="form-control" required>
										</div><!-- End .form-group -->

										<div class="form-group">
											<label>Website</label>
											<input type="url" class="form-control">
										</div><!-- End .form-group -->

										<div class="form-group-custom-control mb-2">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="save-name">
												<label class="custom-control-label" for="save-name">Save my name, email,
													and website in this browser for the next time I comment.</label>
											</div><!-- End .custom-checkbox -->
										</div><!-- End .form-group-custom-control -->

										<div class="form-footer my-0">
											<button type="submit" class="btn btn-sm btn-primary">Post
												Comment</button>
										</div><!-- End .form-footer -->
									</form>
								</div><!-- End .comment-respond -->
							</div><!-- End .post-body -->
						</article><!-- End .post -->

						<hr class="mt-2 mb-1">

						<div class="related-posts">
							<h4>Related <strong>Posts</strong></h4>

							<div class="owl-carousel owl-theme related-posts-carousel" data-owl-options="{
								'dots': false
							}">
								<article class="post">
									<div class="post-media zoom-effect">
										<a href="single.html">
											<img src="<?php echo $asset; ?>assets/images/blog/related/post-1.jpg" alt="Post">
										</a>
									</div><!-- End .post-media -->

									<div class="post-body">
										<div class="post-date">
											<span class="day">29</span>
											<span class="month">Jun</span>
										</div><!-- End .post-date -->

										<h2 class="post-title">
											<a href="single.html">Post Format - Image</a>
										</h2>

										<div class="post-content">
											<p>Euismod atras vulputate iltricies etri elit. Class aptent taciti sociosqu
												ad litora torquent per conubia nostra, per incep tos himens.</p>

											<a href="single.html" class="read-more">read more <i
													class="fas fa-angle-right"></i></a>
										</div><!-- End .post-content -->
									</div><!-- End .post-body -->
								</article>

								<article class="post">
									<div class="post-media zoom-effect">
										<a href="single.html">
											<img src="<?php echo $asset; ?>assets/images/blog/related/post-2.jpg" alt="Post">
										</a>
									</div><!-- End .post-media -->

									<div class="post-body">
										<div class="post-date">
											<span class="day">23</span>
											<span class="month">Mar</span>
										</div><!-- End .post-date -->

										<h2 class="post-title">
											<a href="single.html">Post Format - Image</a>
										</h2>

										<div class="post-content">
											<p>Euismod atras vulputate iltricies etri elit. Class aptent taciti sociosqu
												ad litora torquent per conubia nostra, per incep tos himens.</p>

											<a href="single.html" class="read-more">read more <i
													class="fas fa-angle-right"></i></a>
										</div><!-- End .post-content -->
									</div><!-- End .post-body -->
								</article>

								<article class="post">
									<div class="post-media zoom-effect">
										<a href="single.html">
											<img src="<?php echo $asset; ?>assets/images/blog/related/post-3.jpg" alt="Post">
										</a>
									</div><!-- End .post-media -->

									<div class="post-body">
										<div class="post-date">
											<span class="day">14</span>
											<span class="month">May</span>
										</div><!-- End .post-date -->

										<h2 class="post-title">
											<a href="single.html">Post Format - Image</a>
										</h2>

										<div class="post-content">
											<p>Euismod atras vulputate iltricies etri elit. Class aptent taciti sociosqu
												ad litora torquent per conubia nostra, per incep tos himens.</p>

											<a href="single.html" class="read-more">read more <i
													class="fas fa-angle-right"></i></a>
										</div><!-- End .post-content -->
									</div><!-- End .post-body -->
								</article>

								<article class="post">
									<div class="post-media zoom-effect">
										<a href="single.html">
											<img src="<?php echo $asset; ?>assets/images/blog/related/post-1.jpg" alt="Post">
										</a>
									</div><!-- End .post-media -->

									<div class="post-body">
										<div class="post-date">
											<span class="day">11</span>
											<span class="month">Apr</span>
										</div><!-- End .post-date -->

										<h2 class="post-title">
											<a href="single.html">Post Format - Image</a>
										</h2>

										<div class="post-content">
											<p>Euismod atras vulputate iltricies etri elit. Class aptent taciti sociosqu
												ad litora torquent per conubia nostra, per incep tos himens.</p>

											<a href="single.html" class="read-more">read more <i
													class="fas fa-angle-right"></i></a>
										</div><!-- End .post-content -->
									</div><!-- End .post-body -->
								</article>
							</div><!-- End .owl-carousel -->
						</div><!-- End .related-posts -->
					</div><!-- End .col-lg-9 -->

					<div class="sidebar-toggle custom-sidebar-toggle">
						<i class="fas fa-sliders-h"></i>
					</div>
					<div class="sidebar-overlay"></div>
					<aside class="sidebar mobile-sidebar col-lg-3">
						<div class="sidebar-wrapper" data-sticky-sidebar-options='{"offsetTop": 72}'>
							<div class="widget widget-categories">
								<h4 class="widget-title">Blog Categories</h4>

								<ul class="list">
									<li>
										<a href="#">All about clothing</a>

										<ul class="list">
											<li><a href="#">Dresses</a></li>
										</ul>
									</li>
									<li><a href="#">Make-up &amp; beauty</a></li>
									<li><a href="#">Accessories</a></li>
									<li><a href="#">Fashion trends</a></li>
									<li><a href="#">Haircuts &amp; hairstyles</a></li>
								</ul>
							</div><!-- End .widget -->

							<div class="widget">
								<h4 class="widget-title">Recent Posts</h4>

								<ul class="simple-post-list">
									<li>
										<div class="post-media">
											<a href="single.html">
												<img src="<?php echo $asset; ?>assets/images/blog/widget/post-1.jpg" alt="Post">
											</a>
										</div><!-- End .post-media -->
										<div class="post-info">
											<a href="single.html">Post Format - Video</a>
											<div class="post-meta">
												April 08, 2018
											</div><!-- End .post-meta -->
										</div><!-- End .post-info -->
									</li>

									<li>
										<div class="post-media">
											<a href="single.html">
												<img src="<?php echo $asset; ?>assets/images/blog/widget/post-2.jpg" alt="Post">
											</a>
										</div><!-- End .post-media -->
										<div class="post-info">
											<a href="single.html">Post Format - Image</a>
											<div class="post-meta">
												March 23, 2016
											</div><!-- End .post-meta -->
										</div><!-- End .post-info -->
									</li>
								</ul>
							</div><!-- End .widget -->

							<div class="widget">
								<h4 class="widget-title">Tags</h4>

								<div class="tagcloud">
									<a href="#">ARTICLES</a>
									<a href="#">CHAT</a>
								</div><!-- End .tagcloud -->
							</div><!-- End .widget -->
						</div><!-- End .sidebar-wrapper -->
					</aside><!-- End .col-lg-3 -->
				</div><!-- End .row -->
			</div><!-- End .container -->
		</main>