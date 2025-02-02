<div class="card mt-3">
    <div class="card-body">
        <table class="table align-middle datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Since</th>
                <th>MRR</th>
                <th>NRC</th>
                <th>Term</th>
                <th>Value</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quotes as $quote)
                @if($quote->lead && !$quote->lead->active)
                    @continue
                @endif

                <tr {{$quote->presentable ? "class='bg-light-success'" : null}}>
                    <td>
                        <a href="/admin/quotes/{{$quote->id}}"><span
                                class="badge bg-primary">#{{$quote->id}}</span></a>
                    </td>
                    <td>
                        <a href="/admin/quotes/{{$quote->id}}">
                            {{$quote->name}}
                        </a>
                    </td>
                    <td>{{$quote->created_at->diffForHumans()}}</td>
                    <td>${{moneyFormat($quote->mrr)}}</td>
                    <td>${{moneyFormat($quote->nrc)}}</td>
                    <td>{{$quote->term ? "$quote->term Months" : "MTM"}}</td>
                    <td>${{moneyFormat($quote->analysis->profit)}} <span
                            class="pull-right">{!! $quote->marginBadge !!}</span></td>
                    <td>
                        <a href="/admin/quotes/{{$quote->id}}/download" class="btn btn-link btn-sm text-muted wait"
                           data-bs-toggle="tooltip" data-bs-placement="top" data-anchor=".custom-table"
                           title="Download"><i
                                class="fa fa-download"></i></a>
                        @if($quote->lead)
                            <a href="/admin/leads/{{$quote->lead->id}}/quotes/{{$quote->id}}"
                               class="btn btn-link btn-sm text-muted"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @else
                            <a href="/admin/accounts/{{$quote->account->id}}/quotes/{{$quote->id}}"
                               class="btn btn-link btn-sm text-muted"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                        @endif

                        @if($quote->presentable)
                            <a href="/admin/quotes/{{$quote->id}}/presentable" class="btn btn-link btn-sm text-muted"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Presentable"><i
                                    class="fa fa-check"></i></a>
                        @else
                            <a href="/admin/quotes/{{$quote->id}}/presentable" class="btn btn-link btn-sm text-muted"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Quote is Not Presentable"><i
                                    class="fa fa-exclamation-triangle"></i></a>
                        @endif

                        <a href="/admin/quotes/{{$quote->id}}/send" class="btn btn-link btn-sm text-muted confirm"
                           data-method="GET"
                           data-message="Are you sure you want to send this quote?"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Send Quote"><i
                                class="fa fa-mail-forward"></i>
                        </a>

                        <a href="/admin/quotes/{{$quote->id}}" class="btn btn-link btn-sm text-muted confirm"
                           data-method="DELETE"
                           data-message="Are you sure you want to delete this quote?"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Quote"><i
                                class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
