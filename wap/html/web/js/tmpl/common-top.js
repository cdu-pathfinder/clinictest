$(function (){
	var tmpl = '<div class="header-wrap">'
	        		+'<a href="javascript:history.back();" class="header-back"><span>返回</span></a>'
						+'<h2>商品categories</h2>'
						+'<a href="javascript:void(0)" id="btn-opera" class="i-main-opera">'
					 	+'<span></span>'
				 	+'</a>'
    			+'</div>'
		    	+'<div class="main-opera-pannel">'
		    		+'<div class="main-op-table main-op-warp">'
		    			+'<a href="../index.html" class="quarter">'
		    				+'<span class="i-home"></span>'
		    				+'<p>home</p>'
		    			+'</a>'
		    			+'<a href="javascript:void(0);" class="quarter current">'
		    				+'<span class="i-categroy"></span>'
		    				+'<p>categories</p>'
		    			+'</a>'
		    			+'<a href="javascript:void(0);" class="quarter">'
		    				+'<span class="i-cart"></span>'
		    				+'<p>chart</p>'
		    			+'</a>'
		    			+'<a href="javascript:void(0);" class="quarter">'
		    				+'<span class="i-mine"></span>'
		    				+'<p>Personl Center</p>'
		    			+'</a>'
		    		+'</div>'
		    	+'</div>';
    var render = template.compile(tmpl);
	var html = render();
	$("#header").html(html);
});