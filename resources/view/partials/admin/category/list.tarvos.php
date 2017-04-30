
<div class="card">
    <div class="header">
        <h4 class="title">
            <a style="color: #333333; font-weight: 300;" href=" { $route->route('categoryList')->getRoute() }">
                { ucwords(\Libs\Languages::show("Categories")) }
            </a>
        </h4>
    </div>
    @partial admin/alertshow;

    @if $categories != false;
    <div class="content table-responsive table-full-width">

        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th> { \Libs\Languages::show('Category name') }</th>
                <th> { \Libs\Languages::show('Description') }</th>
                <th> { \Libs\Languages::show('Transactions') }</th>
                <div class="clearfix"></div>

            </tr></thead>
            <tbody>

            @for categories as category;
                <tr>
                    <td> { $category['name'] }</td>
                    <td> { $category['description'] }</td>
                    <td>
                        <a style="padding:1px 5px 1px 5px; margin:0;" href=" { $route->route('category_edit')->param($category['id'])->getRoute() }" type="button" class="btn btn-primary btn-fill"> <i class="material-icons">edit</i> </a>
                        <a style="padding:1px 5px 1px 5px; margin:0;" href=" { $route->route('category_delete')->param($category['id'])->getRoute() }" type="button" class="btn btn-danger btn-fill"> <i class="material-icons">delete</i></a>
                    </td>
                    <div class="clearfix"></div>

                </tr>
            @endforeach;
            </tbody>
        </table>
        <div style="width: 100%; align-items: center; text-align: center;">
            <div class="btn-group btn-group-raised">
            @for $i = 1, $i <= $page, $i++;
                <a type="button" href="?s= { $i } " class="@if !isset($_GET['s']); @if $i==1; active @endif @else  @if $i==$_GET['s']; active @endif @endif btn btn-fill btn-info"> { $i }</a>
            @endfor;
            </div>
        </div>
    </div>
    @else
    <div class="content">
        <div class="alert alert-danger">
            { \Libs\Languages::show('No category') }!
        </div>
        <a href=" { $route->route('categoryAdd')->getRoute() }" class="btn btn-fill btn-info">
            { \Libs\Languages::show('Add category') }
        </a>
    </div>

    @endif
</div>