<?php //dd()?>
              <div class="itemTab">
                            <ul>
                                <li><button type="submit" class="list-group-item @if($capitalized == 'null' && $category_id == 'null') active @endif" onclick="location.href='{{ route('leasevaluation.index')}}'">Consolidated</button></li>


                                @if($capitalized ==1 || $capitalized =='null')
                                  @foreach($lease_asset_categories as $key=>$value) 
                                <li>
                                <button type="submit" class="list-group-item @if($capitalized ==1 && $category_id == $value['id']) active @endif" onclick="location.href='{{ route('leasevaluation.index',['capitalized' => 1,'id' => $value['id'] ])}}'">{{$value['title']}}</a></button>
                           </li>
                                @endforeach
                               
                                @else
                               <li> <button type="submit" class="list-group-item">Short Term Lease</button></li>
                                <li><button type="submit" class="list-group-item">Low Value Lease</button></li>
                                 @foreach($lease_asset_noncategories as $key=>$value) 
                                <li>
                                <button type="submit" class="list-group-item @if($capitalized == 0 && $category_id == $value['id']) active @endif" onclick="location.href='{{ route('leasevaluation.index',['capitalized' => 0,'id' => $value['id'] ])}}'">{{$value['title']}}</a></button>
                           </li>
                                @endforeach
                                @endif
                              
                            </ul>
                    </div>