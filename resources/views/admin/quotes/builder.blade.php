<div class="row mt-3">


    <div class="col-lg-9">
        @if($quote->analysis->margin < setting('quotes.margin'))
            <div role="alert" class="alert border-danger">
                <i class="fa fa-exclamation-triangle"></i> WARNING: This quote does not meet the minimum profit margin
                of {{setting('quotes.margin')}}% to be a viable deal.
            </div>
        @endif



        @include('admin.quotes.build.quote_items')



            <div class="row mt-3">
                <div class="col-lg-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <span class="fs-6">Recurring</span>
                            <br/>
                            <b>${{moneyFormat($quote->mrr)}}</b>
                        </div>

                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <span class="fs-6">One-Time</span>
                            <br/>
                            <b>${{moneyFormat($quote->nrc)}}</b>
                        </div>
                    </div>
                </div>
                @if($quote->discount > 0)
                <div class="col-lg-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <span class="fs-6">Discounted</span>
                            <br/>
                            <b>-${{moneyFormat($quote->discount)}}</b>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <span class="fs-6">Subtotal</span>
                            <br/>
                            <b>${{moneyFormat($quote->subtotal)}}</b>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <span class="fs-6">Tax</span>
                            <br/>
                            <b>${{moneyFormat($quote->tax)}}</b>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <span class="fs-6">Total Quote</span>
                            <br/>
                            <b><span class="text-primary">${{moneyFormat($quote->total)}}</span></b>
                        </div>
                    </div>
                </div>
            </div>


    </div>

    <div class="col-lg-3">
        @if($quote->items()->count())
            <div class="mb-2 rightpanel">
                <a class="btn btn-{{bm()}}primary confirm w-100 mt-3" href="/admin/quotes/{{$quote->id}}/send"
                   data-message="Are you sure you want to send this quote? This will also mark this quote as 'presentable' and will be available for review in the contact's discovery page."
                   data-method="GET"><i class="fa fa-send"></i> Send Quote</a>
                <a class="btn btn-{{bm()}}info wait w-100 mt-3" data-message="Generating Quote.." data-anchor=".rightpanel"
                   href="/admin/quotes/{{$quote->id}}/download"><i class="fa fa-download"></i> Download</a>
                @if(!$quote->presentable)
                    <a class="btn btn-{{bm()}}danger w-100 mt-3" href="/admin/quotes/{{$quote->id}}/presentable"><i
                            class="fa fa-exclamation"></i> Not Presentable</a>
                @else
                    <a class="btn btn-{{bm()}}success w-100 mt-3" href="/admin/quotes/{{$quote->id}}/presentable"><i
                            class="fa fa-check"></i> Presentable</a>

                @endif
            </div>
        @endif

        <ul class="nav nav-tabs tab-card" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#profit" role="tab">
                    Profit ({{$quote->analysis->margin}}%)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#settings" role="tab">Settings</a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="settings" role="tabpanel">
                @include('admin.quotes.settings')
            </div>
            <div class="tab-pane fade show active" id="profit" role="tabpanel">
                @include('admin.quotes.stat')
            </div>
        </div>


    </div>
</div>

@include('admin.quotes.build.add_modals')


