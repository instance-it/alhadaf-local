<style type="text/css">
.mobile_footer{
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  height:50px;
  background:#FFFFFF;
  text-align: center;
  justify-content:space-between;
  display:block;
  padding:5px 10px 2px 10px;
  border:none;
  border-radius:10px 10px 0 0;
  -webkit-box-shadow: 0px 0px 10px rgb(0 0 0 / 20%), 0px 0px 10px rgb(255 255 255 / 30%);
        box-shadow: 0px 0px 10px rgb(0 0 0 / 20%), 0px 0px 10px rgb(255 255 255 / 30%);
  z-index: 999;
}

.mobile_footer ul{
  width: 100%;
  justify-content:space-between;
  display:flex;
  padding:0;
  margin:0;
}
.mobile_footer li{
	list-style:none;
  margin: 0;
}

.mobile_footer li a{
	text-decoration:none;
	font-size: 24px !important;
  color: #477fae;
  line-height:1;
  font-weight:normal;
  position: relative;
  display: block;
  padding: 8px 10px;
  border: none;
  cursor: pointer;
}

.mobile_footer li a:hover{
	color: #000000;
}

.mobile_footer li a i{
    font-size:24px;
}

.mobile_footer li a.navbar-toggler span.fa-times,
.mobile_footer li a.navbar-toggler.active span.fa-bars {
  display:none;
}

.mobile_footer li a.navbar-toggler.active span.fa-times {
  display:inline-block;
}

.mobile_footer li a span {
  line-height: 1;
  width: 30px;
  font-size:24px;
  letter-spacing: 1.5px;
}

.mobile_footer li a img{
  padding: 0;
  width: 27px;
}
header .header-nav .nav-container.breakpoint-on .nav-menu {
  padding-bottom: 80px;
}
.mobile_footer li.mobile-center a {
    top: -30px;
    background: #477fae;
    border-radius: 10px;
    color: #FFFFFF;
    width: 60px;
    height: 60px;
    border: 0px solid #477fae;
    padding: 5px;
    -webkit-box-shadow: 0px -10px 10px -8px rgb(0 0 0 / 0.20), 0px 0px 10px rgb(255 255 255 / 30%);
        box-shadow: 0px -10px 10px -8px rgb(0 0 0 / 0.20), 0px 0px 10px rgb(255 255 255 / 30%);
}
.mobile_footer li.mobile-center a img {
    width: 50px;
    height: 50px;
    max-width: inherit;
    margin-top: 0;
    margin-left: 0px;
    line-height: 50px;
}
.mobile_footer li.mobile-center a span {
  line-height: 50px;
}
.mobile_footer li a.mobile-menu.active span:before{
  content: "\f00d";
}
.mobile_footer li a.home-menu {
  position: static;
}
@media screen and (min-width: 992px){
  .mobile_footer{
    display:none !important;
  }
}
@media screen and (max-width: 991px){
  .footer {
    padding-bottom: 80px !important;
  }
}
</style>

<div class="mobile_footer">
  <ul>
    <li><a href="javascript:void(0);" class="mobile-nav-toggler"><span class="fal fa-bars"></span></a></li>
    <li><a href="#"><span class="fal fa-info"></span></a></li>
    <li class="mobile-center"><a href="javascript:void(0);" class="clsparentmenu" pagename="home"><span class="far fa-home"></span></a></li>
    <li><a href="javascript:void(0);" class="clsparentmenu" pagename="home"><span class="fal fa-user-headset"></span></a></li>
    <li><a href="javascript:void(0);" class="clsparentmenu" pagename="home"><span class="fal fa-user"></span></a></li>
  </ul>
</div>



