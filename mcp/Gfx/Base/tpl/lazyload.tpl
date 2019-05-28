    <script type="text/javascript">
    window.no_image='/images/no-image.gif';
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
    console.log("check ipadmode.....");
    console.log(isiPad);
    /// lazy Img Load
    document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img"));
        if ("IntersectionObserver" in window) {
            window.lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var lazyImage = entry.target;
                        console.log("load image "+lazyImage.dataset.src );
                        console.log("load page width "+lazyImage.width );
                        console.log("load page height "+lazyImage.height );
                        lazyImage.src = lazyImage.dataset.src+"?w="+lazyImage.width+"&h="+lazyImage.height;
                        lazyImage.onerror= function (){
                            this.onerror=null;
                            this.src=window.no_image;
                        }
                        lazyImageObserver.unobserve(lazyImage);
                        console.log("loaded image "+lazyImage.src );
                        console.log("loaded real width "+lazyImage.naturalWidth );
                        console.log("loaded real height "+lazyImage.naturalHeight );
                        console.log("loaded page width "+lazyImage.width );
                        console.log("loaded page height "+lazyImage.height );
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
