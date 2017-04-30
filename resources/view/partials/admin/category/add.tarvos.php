
        <div class="card">
            <div class="header">
                <h4 class="title">
                    @if isset($edit);
                    <a style="color: #333333; font-weight: 300;" href=" { $route->route('categoryList')->getRoute() }">
                        { ucwords(\Libs\Languages::show("Edit category")) }
                    </a>
                    @else
                    <a style="color: #333333; font-weight: 300;" href=" { $route->route('categoryAdd')->getRoute() }">
                        { ucwords(\Libs\Languages::show("Add category")) }
                    </a>
                    @endif
                </h4>
            </div>
            <div class="content ">

                <div class="row">

                    <div class="col-md-12">

                        @partial admin/alertshow;

                        <form method="post" action="@if isset($edit); @else { $route->route('categoryAdd')->getRoute() } @endif">

                        <div class="form-group">
                            <label> { \Libs\Languages::show("Category name") }</label>
                            <input type="text" name="category_name" placeholder=" { \Libs\Languages::temporarilySet(['tr_TR' => 'Buraya kategori ismi gelecek', 'en_US' => 'Category name here']) }" @if isset($edit); value=" { $name }" @endif class="form-control">
                        </div>
                        <div class="form-group">
                            <label> { \Libs\Languages::show("Description") }</label>
                            <textarea rows="5" name="category_description" class="form-control" placeholder=" { \Libs\Languages::temporarilySet(['tr_TR' => 'Buraya kategori açıklaması gelecek', 'en_US' => 'Category description here']) }">@if isset($edit); { $description } @endif</textarea>
                        </div>

                        <button type="submit" class="btn btn-info btn-fill center-block"> @if isset($edit); { ucwords(\Libs\Languages::show("Edit category")) } @else  { ucwords(\Libs\Languages::show("Add category")) } @endif</button>
                        <div class="clearfix"></div>

                        </form>

                    </div>

                </div>

            </div>
        </div>