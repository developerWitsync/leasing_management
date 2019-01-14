<div class="panel panel-info">
    <div class="panel-heading">
        {{ $category->title }}
        <span>
            <a href="{{ route('settings.leaseassets.addcategorysetting', ['id' => $category->id]) }}" class="btn btn-sm btn-primary pull-right add_more">Add More</a>
        </span>
    </div>
    <div class="panel-body">
        <div class="panel-body settingTble">
            <table class="table table-condensed {{ str_slug($category->title) }}">
                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Title</th>
                    <th>Depreciation Method</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>