    <script type="text/javascript">
    window.no_image='<?php echo lnxmcp()->getCommon('ucm.noimage'); ?>';
    window.ucm_url='<?php echo lnxmcp()->getCommon('ucm.url'); ?>';
    window.ucm_debug='<?php echo lnxmcp()->getCommon('ucm.debug'); ?>';
    function ucm_debug($message) {
        if (window.ucm_debug=="true") {
            console.log(message);
        }
    }
    function lazyJsLoad($url) {
      var element = document.createElement("script");
      element.src = $url;
      document.body.appendChild(element);
    }
    // For use within normal web clients 
    var isiPad = navigator.userAgent.match(/iPad/i) != null;
    // For use within iPad developer UIWebView
    var ua = navigator.userAgent;
    var isiPad = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);
    ucm_debug("check ipadmode.....");
    ucm_debug(isiPad);
    /// lazy Img Load
    document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img"));
        if ("IntersectionObserver" in window) {
            window.lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var lazyImage = entry.target;
                        ucm_debug("load image "+lazyImage.dataset.src );
                        ucm_debug("load page width "+lazyImage.width );
                        ucm_debug("load page height "+lazyImage.height );
                        if (window.ucm_url==''){
                            lazyImage.src = lazyImage.dataset.src+"?w="+lazyImage.width+"&h="+lazyImage.height;
                        } else {
                            lazyImage.src = window.ucm_url+"?file="+lazyImage.dataset.src+"&w="+lazyImage.width+"&h="+lazyImage.height;
                        }
                        lazyImage.onerror= function (){
                            this.onerror=null;
                            this.src=window.no_image;
                        }
                        lazyImageObserver.unobserve(lazyImage);
                        ucm_debug("loaded image "+lazyImage.src );
                        ucm_debug("loaded real width "+lazyImage.naturalWidth );
                        ucm_debug("loaded real height "+lazyImage.naturalHeight );
                        ucm_debug("loaded page width "+lazyImage.width );
                        ucm_debug("loaded page height "+lazyImage.height );
                    }
                });
            });
            lazyImages.forEach(function(lazyImage) {
                if (lazyImage.complete!=true ) {
                    if(lazyImage.dataset.src==null){
                        lazyImage.dataset.src=lazyImage.src;
                        lazyImage.src='/css/loading.svg';
                        lazyImageObserver.observe(lazyImage);
                    }
                }
            });
        }
    });
    </script>
