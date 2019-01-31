                    <div class="itemTab">
                            <ul>
                                <li class="list-group-item @if(request()->segment('1') == 'lease-valuation') active @endif">Consolidated</li>
                                @foreach($lease_asset_categories as $key=>$value) 
                                <li>
                               <button type="submit" class="list-group-item @if(request()->segment('2') == 'noncapitalized') active @endif" onclick="location.href='{{ route('leasevaluation.index',['id' => $value['id'] ])}}'">{{$value['title']}}</a></button>
                           </li>
                                @endforeach
                            </ul>
                    </div>