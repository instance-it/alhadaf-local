
    
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing">
                <div class="widget">
                    <div class="widget-heading m-0">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 mx-auto">
                                <div class="input-group">
                                    <input type="search" class="form-control control-append" placeholder="Search..." id="inputMasters">
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="labelMasters" id="labelMasters"><i class="fal fa-search"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 layout-spacing">
                <div class="row ml-0" id="MasterMenuList">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $('body').on('click','.MasterMenu', function () {
            var pagename = $(this).attr('pagename');
            if(pagename)
            {
                currentXhr.abort();
                render(pagename);
                window.history.pushState(pagename, 'Title', dirpath + pagename);
            }
        });

        $("#inputMasters").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#MasterMenuList div .card-title").filter(function() {
                $(this).parent().parent().parent().parent().toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
