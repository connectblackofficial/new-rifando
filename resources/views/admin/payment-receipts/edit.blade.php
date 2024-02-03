<form method="POST" action="{{ route($routeUpdate,['pk'=>$row->id]) }}" onsubmit="return sendFormAfterConfirm($(this))"
      accept-charset="UTF-8"
      class="form-horizontal" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    @include ('admin.payment-receipts.form', ['formMode' => 'edit'])
</form>