
							<div class="col-md-3 right-bar">
								<div class="header-sidebar">Berita Properti Terbaru</div>
								<div class="content-sidebar">
									
                                    <?php

                                        $no =0;
                                    foreach ($this->news_side_bar as $key) {

									$no++;
                                     ?>

									<div class="row">
										<div class="col-sm-6">
											<a  href="<?php echo base_url()."news/detail/".$key->id."/".url_title($key->title) ?>">
												<img src="<?php echo base_url() ?>assets/uploads/news/<?php echo $key->id?>/<?php echo $key->images ?>" width="100%">
											</a>
										</div>
										<div class="col-sm-6">
										<a class="wd" href="<?php echo base_url()."news/detail/".$key->id."/".url_title($key->title) ?>">
										<?php echo $key->title ?>
											</a>
										</div>
									</div>
									<?php } ?>

									<div class="row">
										<div class="col-md-12">
											<img class="sidebar-bn" src="<?php echo base_url()."/assets/theme/img/sentul/safron.jpg"?>"  /> 
										</div>
									</div>

									<div class="row">
										<div class="col-md-12 page-fb">
										<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FRumahrumahsentulcity&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=254507101579491" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
										</div>
									</div>

								</div>
							</div>