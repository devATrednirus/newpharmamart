<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
    <div class="row">
        <div class="col-md-12">
            @if (isset($translations))
                @if (count($translations))
                    <p>{!! trans('admin::messages.Translations of this :entity', ['entity' => $xPanel->entity_name]) !!}:</p>
    			
                    <table class="table table-condensed table-bordered" style="m-t-10">
                        <thead>
                        <tr>
                            <th>{{ trans('admin::messages.Language') }}</th>
    						
                            {{-- Table columns --}}
                            @foreach($xPanel->columns as $column)
                                <th>{{ $column['label'] }} </th>
                            @endforeach

                            @if ($xPanel->hasAccess('update') or $xPanel->hasAccess('delete'))
                                <th>{{ trans('admin::messages.actions') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($translations as $key => $entry)
                            {{-- Don't try to show missing or deactivated languages translations --}}
                            @if (!isset($entry->language) or !isset($entry->language->active) or $entry->language->active != 1)
                                @continue;
                            @endif
                            <tr>
                                <td>{{ $entry->language->name }}</td>
    							
                                @foreach($xPanel->columns as $column)
    								
                                    @if (isset($column['type']) && $column['type']=='select_multiple')
                                        {{-- relationships with pivot table (n-n) --}}
                                        <td>
                                            <?php
                                            $results = $entry->{$column['entity']}()->getResults();
                                            if ($results && $results->count()) {
                                                $results_array = $results->lists($column['attribute'], 'id');
                                                echo implode(', ', $results_array->toArray());
                                            } else {
                                                echo '-';
                                            }
                                            ?>
    									</td>
                                    @elseif (isset($column['type']) && $column['type']=='select')
                                        {{-- single relationships (1-1, 1-n) --}}
                                        <td>
    										<?php
                                            if ($entry->{$column['entity']}()->getResults()) {
                                                echo $entry->{$column['entity']}()->getResults()->{$column['attribute']};
                                            }
                                            ?>
    									</td>
                                    @elseif (isset($column['on_display']) && $column['on_display']=='checkbox')
    									{{-- checkbox display object attribute --}}
    									<td>{!! checkboxDisplay($entry->{$column['name']}) !!}</td>
                                    @else
                                        {{-- regular object attribute --}}
                                        <td>{{ str_limit(strip_tags($entry->{$column['name']}), 80, "[...]") }}</td>
                                    @endif

                                @endforeach

                                @if ($xPanel->hasAccess('update') or $xPanel->hasAccess('delete'))
                                    <td>
                                        @if ($xPanel->hasAccess('update'))
                                            <a href="{{ str_replace('/'.$original_entry->id, '/'.$entry->id, str_replace('details', 'edit', Request::url())) }}" class="btn btn-xs btn-default">
                                                <i class="fa fa-edit"></i> {{ trans('admin::messages.edit') }}
                                            </a>
                                        @endif
                                        @if ($xPanel->hasAccess('delete'))
                                            <a href="{{ str_replace('/'.$original_entry->id, '/'.$entry->id, str_replace('details', '', Request::url())) }}" class="btn btn-xs btn-default" data-button-type="delete">
                                                <i class="fa fa-trash"></i> {{ trans('admin::messages.delete') }}
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    {!! trans('admin::messages.No translations available.') !!}<br><br>
                @endif

                @if ($languages_to_translate_in->count())
    				{!! trans('admin::messages.Add translation to') !!}:
                    @foreach($languages_to_translate_in as $lang)
                        <a class="btn btn-xs btn-default" href="{{ str_replace('details', 'translate/'.$lang->abbr, Request::url()) }}"><i class="fa fa-plus"></i> {{ $lang->name }}</a>
                    @endforeach
                @endif
            @elseif($xPanel->getRoute()=="admin/messages" && $entry)
 
                 <div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer's Contact Details</div>


                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                    <tbody>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Name</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle"> {{isset($entry->from_name)?$entry->from_name:''}}</td>
                        </tr> 
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>E-mail</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{isset($entry->from_email)?$entry->from_email:''}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Mobile</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->from_phone}}</td>
                        </tr>
                         
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>City</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->city}}</td>
                        </tr>
                     
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Address</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->address}}</td>
                        </tr>
                        
                    </tbody>
                </table>
                
                <div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer is looking for "{{$entry->looking_for}}"</div>

                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                    <tbody>
                        <tr>
                            <td colspan="3" style="padding:4px 0;" valign="middle">{{$entry->message}}</td>
                        </tr>

                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Your Franchise Location</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->location}}</td>
                        </tr>
                        
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Do You Have Drugs License?</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->drugs_license}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Do You Have GST Number?</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->have_gst_number}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Select purchase period</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->purchase_period}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Select Call Back Time</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->call_back_time}}</td>
                        </tr>
                       <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Profession</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->profession}}</td>
                        </tr>
                       <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Minimum Investment</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->minimum_investment}}</td>
                        </tr>
                    </tbody>
                </table>

                @if($entry->shared->count() > 0 || $entry->buy->count() > 0 )
                <div class="row">
                    <div class="col-md-6">
                        <h2>Shared Leads</h2>
                        <table cellspacing="0" width="100%" class="table responsive" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                            <thead>
                                <tr>
                                    <th width="50%">Company Name</th>
                                    <th>Package</th>
                                </tr>
                            </thead>
                            <tbody>
                                

                                 @foreach($entry->shared as $shared)
                                    <tr>
                                        <td >{{$shared->receiver->name}}</td>
                                        <td >{{$shared->receiver->package->name}}</td>
                                    </tr>
                                @endforeach
                                 
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h2>Buy Leads</h2>
                        <table cellspacing="0" width="100%" class="table responsive" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                            <thead>
                                <tr>
                                    <th width="50%">Company Name</th>
                                    <th>Package</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                

                                 @foreach($entry->buy as $shared)
                                    <tr>
                                        <td >{{$shared->receiver->name}}</td>
                                        <td >{{$shared->receiver->package->name}}</td>
                                        <td >{{$shared->created_at}}</td>
                                    </tr>
                                @endforeach
                                 
                            </tbody>
                        </table>
                    </div>
                </div>

                @endif
                <div>
                    {!! nl2br($entry->sending_log) !!}
                </div>
            @elseif($xPanel->getRoute()=="admin/queries")
               
                <div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer's Contact Details</div>

                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                    <tbody>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Name</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle"> {{$entry->name}}</td>
                        </tr> 
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>E-mail</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->from_email}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Mobile</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->phone}}</td>
                        </tr>
                         
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>City</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{($entry->city?$entry->city->name:$entry->city_name)}}</td>
                        </tr>
                  
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Address</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->address}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>IP Address</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->ip_address}}</td>
                        </tr>
                        
                    </tbody>
                </table>
                
                <div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer is looking for "{{$entry->looking_for}}"</div>

                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                    <tbody>
                        <tr>
                            <td colspan="3" style="padding:4px 0;" valign="middle">{{$entry->query}}</td>
                        </tr>



                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Your Franchise Location</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->location}}</td>
                        </tr>
                        
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Do You Have Drugs License?</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->drugs_license}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Do You Have GST Number?</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->have_gst_number}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Select purchase period</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->purchase_period}}</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Select Call Back Time</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->call_back_time}}</td>
                        </tr>
                       <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Profession</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->profession}}</td>
                        </tr>
                       <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Minimum Investment</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->minimum_investment}}</td>
                        </tr>

                        <tr>
                            <td style="padding:4px 0" valign="middle"><strong>Any specific query</strong></td>
                            <td align="left" width="8" valign="middle"><strong>:</strong></td>
                            <td valign="middle">{{$entry->search_term}}</td>
                        </tr>
                    </tbody>
                </table>

                @if($entry->shared->count() > 0 )
                <div class="row">
                    <div class="col-md-6">
                        <table cellspacing="0" width="100%" class="table responsive" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                            <thead>
                                <tr>
                                    <th width="50%">Company Name</th>
                                    <th>Package</th>
                                </tr>
                            </thead>
                            <tbody>
                                

                                 @foreach($entry->shared as $shared)
                                    <tr>
                                        <td >{{$shared->receiver->name}}</td>
                                        <td >{{$shared->receiver->package->name}}</td>
                                    </tr>
                                @endforeach
                                 
                            </tbody>
                        </table>

                    </div>
                </div>

                

                @endif
            @elseif($xPanel->getRoute()=="admin/enquiries")

                    {{$entry->message}}

            @elseif($xPanel->getRoute()=="admin/payments")

                <div class="row">
                    <div class="col-md-12">
                        <table cellspacing="0" width="100%" class="table responsive" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                            <thead>
                                <tr>
                                    <th >Updated By</th>
                                    <th>Previous End date</th>
                                    <th>New End date</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                

                                 @foreach($entry->histories as $history)
                                    <tr>
                                        <td >{{$history->user->name}}</td>
                                        <td >{{$history->previous_end_date}}</td>
                                        <td >{{$history->end_date}}</td>
                                        <td >{{$history->created_at}}</td>
                                    </tr>
                                @endforeach
                                 
                            </tbody>
                        </table>

                    </div>
                </div>
                   
            @endif


            
        </div>
    </div>
</div>
