<div class="row">

        <div class="col-md-5">
            <img class="img-fluid" src="<?=imageAsset($row->document)?>"/>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <?php
                    $cols = ["id", "product_name", "valor", "customer_name", "customer_phone"];
                    ?>
                    @foreach($cols as $c)
                        <tr>
                            <th scope="row"><?= htmlLabel($c) ?></th>
                            <td><?= itemRowView($formatFieldsFn, $row, $c) ?></td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>

</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label"><?= htmlLabel('status') ?></label>
    <select name="status" class="form-control" id="status">
        @foreach (json_decode('{"Recusado":"Recusado","Aprovado":"Aprovado","Pendente":"Pendente"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ (isset($row->status) && $row->status == $optionKey) ? 'selected' : old('status')}}>{{ htmlLabel(strtolower($optionValue))}}</option>
        @endforeach
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('comments') ? 'has-error' : ''}}">
    <label for="comments" class="control-label"><?= htmlLabel('comments') ?></label>
    <textarea class="form-control" rows="5" name="comments" type="textarea"
              id="comments">{{ isset($row->comments) ? $row->comments : old('comments')}}</textarea>
    {!! $errors->first('comments', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary text-capitalize" type="submit"
           value="{{ $formMode === 'edit' ? htmlLabel('update') : htmlLabel('create') }}">
</div>
