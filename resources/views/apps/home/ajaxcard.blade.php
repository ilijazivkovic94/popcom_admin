<div class="col-lg-6 col-12">
								<?php //print_r($customerData); ?>		
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Customers</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A visitor who adds products to cart and either completes sale or abandons cart"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-customer-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg"><?php echo number_format($customerData['count']) ?></a>
												<?php 
													$diffPrice = ($customerData['count'] - $customerData['prevcount']);
													
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="text-muted"><i class="fas {{ $caretIcon }} "></i> <?php echo number_format($diffPrice) ?></span>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-12">
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Visitors</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title='A person who touches the POS – “visits the machine” '><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-visitors-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg"><?php echo number_format($visitorData['count']) ?></a>
												<?php 
													$diffPrice = ($visitorData['count'] - $visitorData['prevcount']);
													//$diffPrice = 0;
													$caretIcon = ($diffPrice >= 0) ? 'fa-caret-up' : 'fa-caret-down';
												?>
												<span class="text-muted"><i class="fas {{ $caretIcon }} "></i> <?php echo number_format($diffPrice) ?></span>
											</div>
										</div>
									</div>
								

								<div class="col-lg-6 col-12">
										<div class="d-flex align-items-center">
											<h4 class="mb-0 mr-2">Lookers</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A person whose full face is detected for 5 seconds or more"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-lookers-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">0</a>
												<span class="text-muted"><i class="fas fa-caret-up"></i> 0</span>
											</div>
										</div>
									</div>
								

								<div class="col-lg-6 col-12">
										<div class="d-flex">
											<h4 class="mb-0 mr-2">Passers</h4>
											<a href="#" class="infoLink" data-toggle="tooltip" data-placement="top" title="A person detected passing the machine within 35 feet"><i class="fas fa-info-circle"></i></a>
										</div>
										<div class="d-flex align-items-center mb-10 mt-4">
											<div class="symbol symbol-40 symbol-light-primary mr-5">
												<span class="symbol-label">
													<span class="svg-icon svg-icon-xl svg-icon-primary">
														<img src="{{ asset('images/home/icon-passers-circle.png') }}" />
													</span>
												</span>
											</div>
											<div class="d-flex flex-column font-weight-bold">
												<a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">0</a>
												<span class="text-muted"><i class="fas fa-caret-up"></i> 0</span>
											</div>
										</div>
									</div>