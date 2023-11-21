{{--
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
 
				<div   style="padding: 20px ">
					 
					 
						<?php
						if (isset($conversation) && !empty($conversation) > 0):
						
							// Conversation URLs
							$consUrl = lurl('account/conversations');
							$conDelAllUrl = lurl('account/conversations/' . $conversation->id . '/messages/delete');
						?>
						<div class="table-responsive">
							<form name="listForm" method="POST" action="{{ $conDelAllUrl }}">
								{!! csrf_field() !!}
								
								@if($routeName=="conversations")
								     <div class="row">
										<div class="col-lg-12">

												@if($previous)
											 	
								                  <a href="{{ lurl('/account/conversations/'.$previous->id.'/messages')}}" class="btn btn-success conversation_modal_other"  data-toggle="modal">Previous</a>
								                @endif


								                    @if($next)
								                    <a href="{{ lurl('/account/conversations/'.$next->id.'/messages')}}" class="btn btn-success pull-right conversation_modal_other"  data-toggle="modal">Next</a>
								                    @endif
								        </div>
								    </div>

								 @endif
								        

								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										<th data-sort-ignore="true" colspan="3">
																						{{ t("Conversation") }} #{{ $conversation->id }}&nbsp;|&nbsp;
											{{ $conversation->subject }}
										</th>
									</tr>
									</thead>
									<tbody>
									<!-- Main Conversation -->
									<tr>
										<td colspan="3">
											<strong>{{ t("Sender's Name") }}:</strong> {{ $conversation->from_name ?? '--' }}<br>
											<strong>{{ t("Sender's Email") }}:</strong> {{ $conversation->from_email ?? '--' }}<br>
											<strong>{{ t("Sender's Phone") }}:</strong> {{ $conversation->from_phone ?? '--' }}<br>
											<strong>Location:</strong> {{ $conversation->location ?? '--' }}<br>
											<strong>address:</strong> {{ $conversation->address ?? '--' }}<br>
											<strong>city:</strong> {{ $conversation->city ?? '--' }}<br>
											 
											<strong>drugs_license:</strong> {{ $conversation->drugs_license ?? '--' }}<br>
											<strong>have_gst_number:</strong> {{ $conversation->have_gst_number ?? '--' }}<br>
											<strong>purchase_period:</strong> {{ $conversation->purchase_period ?? '--' }}<br>
											<strong>call_back_time:</strong> {{ $conversation->call_back_time ?? '--' }}<br>
											<strong>profession:</strong> {{ $conversation->profession ?? '--' }}<br>
											<strong>Looking For:</strong> {{ $conversation->looking_for ?? '--' }}<br>

											@if($conversation->type=="buy")
											<strong>Purchased at:</strong> {{ $conversation->created_at->format('d-M-Y H:i a') ?? '--' }}<br>
											@else
											<strong>Submitted at:</strong> {{ $conversation->created_at->format('d-M-Y H:i a') ?? '--' }}<br>
											@endif
											


											@if($conversation->verified_status)
											<strong>Verified By:</strong> {{ $conversation->verified_status ?? '--' }}<br>
											@else
											<strong>Verified By:</strong> By OTP<br>
											@endif
											<hr>
											{!! nl2br($conversation->message) !!}
											@if (!empty($conversation->filename) and \Storage::exists($conversation->filename))
												<br><br><a class="btn btn-info" href="{{ \Storage::url($conversation->filename) }}">{{ t('Download') }}</a>
											@endif
											

											{{--
											<hr>
											<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#replyTo{{ $conversation->id }}">
												<i class="icon-reply"></i> {{ t('Reply') }}
											</a>
											--}}
										</td>
									</tr>
									<!-- All Conversation's Messages -->
									<?php
									if (isset($messages) && $messages->count() > 0):
										foreach($messages as $key => $message):
									?>
									<tr>
										@if ($message->from_user_id == auth()->user()->id)
											<td class="add-img-selector">
												<div class="checkbox" style="width:2%">
													<label><input type="checkbox" name="entries[]" value="{{ $message->id }}"></label>
												</div>
											</td>
											<td style="width:88%;">
												<div style="word-break:break-all;">
													<strong>
														<i class="icon-reply"></i> {{ $message->from_name }}:
													</strong><br>
													{!! nl2br($message->message) !!}
													@if (!empty($message->filename) and \Storage::exists($message->filename))
														<br><br><a class="btn btn-info" href="{{ \Storage::url($message->filename) }}">{{ t('Download') }}</a>
													@endif
												</div>
											</td>
											<td class="action-td" style="width:10%">
												<div>
													<p>
														<?php $conDelUrl = lurl('account/conversations/' . $conversation->id . '/messages/' . $message->id . '/delete'); ?>
														<a class="btn btn-danger btn-sm delete-action" href="{{ $conDelUrl }}">
															<i class="fa fa-trash"></i> {{ t('Delete') }}
														</a>
													</p>
												</div>
											</td>
										@else
											<td colspan="3">
												<div style="word-break:break-all;">
													<strong>{{ $message->from_name }}:</strong><br>
													{!! nl2br($message->message) !!}
													@if (!empty($message->filename) and \Storage::exists($message->filename))
														<br><br><a class="btn btn-info" href="{{ \Storage::url($message->filename) }}">{{ t('Download') }}</a>
													@endif
												</div>
											</td>
										@endif
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>
								
								@if (isset($messages) && $messages->count() > 0)
								<div class="table-action">
									<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} |
										<button type="submit" class="btn btn-sm btn-default delete-action">
											<i class="fa fa-trash"></i> {{ t('Delete') }}
										</button>
									</label>
								</div>
								@endif
								
							</form>
						</div>
						
						<nav>
							{{ (isset($messages)) ? $messages->links() : '' }}
						</nav>
						<?php endif; ?>
						
						<div style="clear:both"></div>
					
				 
				</div>
				<!--/.page-content-->

				
				
<script type="text/javascript">

	@if($routeName!="conversations")
	
	$('#{{$conversation->message_id}}').attr('href','{{ lurl('account/conversations/' . $conversation->id . '/buy-messages') }}');
	
	
	@endif
	$('.conversation_modal_other').click(function(e) {
	            e.preventDefault();
	            var href = $(this).attr('href');
	            console.log(href);
	            if(href){
		             $.get(href, function(data) {
		                    $('#conversation_container .modal-content').html(data);
		                });

	            }
	            else{

	            	$('#conversation_container .modal-content').html("");
	            }
	        });

</script>