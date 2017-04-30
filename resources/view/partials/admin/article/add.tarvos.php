
        <div class="card">
            <div class="header">
                <h4 class="title">
                    @if isset($edit);
                    <a style="color: #333333; font-weight: 300;" href=" { $route->route('articleList')->getRoute() }">
                        { ucwords(\Libs\Languages::show("Edit article")) }
                    </a>
                    @else
                    <a style="color: #333333; font-weight: 300;" href=" { $route->route('articleAdd')->getRoute() }">
                        { ucwords(\Libs\Languages::show("Add article")) }
                    </a>
                    @endif
                </h4>
            </div>
            <div class="content ">

                <div class="row">

                    <div class="col-md-12">

                        @partial admin/alertshow;

                        <form method="post" action="@if isset($edit); @else { $route->route('articleAdd')->getRoute() } @endif">

                        <div class="form-group">
                            <label> { \Libs\Languages::show("Article title") }</label>
                            <input type="text" name="article_title" placeholder=" { \Libs\Languages::temporarilySet(['tr_TR' => 'Buraya makale başlığı gelecek', 'en_US' => 'Article title here']) }" @if isset($edit); value=" { $name }" @endif class="form-control">
                        </div>
                        <div class="form-group">
                            <label> { \Libs\Languages::show("Content") }</label>
                            <textarea rows="5" name="article_content" class="tiny form-control" placeholder=" { \Libs\Languages::temporarilySet(['tr_TR' => 'Buraya makale içeriği gelecek', 'en_US' => 'Article content here']) }">@if isset($edit); { $content } @endif</textarea>
                        </div>

                        <button type="submit" class="btn btn-info btn-fill center-block"> @if isset($edit); { ucwords(\Libs\Languages::show("Edit article")) } @else  { ucwords(\Libs\Languages::show("Add article")) } @endif</button>
                        <div class="clearfix"></div>

                        </form>

                    </div>

                </div>

            </div>
        </div>