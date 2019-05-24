
    <script type="text/javascript">
    function lazyJsLoad($url) {
      var element = document.createElement("script");
      element.src = $url;
      document.body.appendChild(element);
    }
    /// lazy Img Load
    document.addEventListener("DOMContentLoaded", function() {
      var lazyImages = [].slice.call(document.querySelectorAll("img"));
        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        console.log("load image "+lazyImage.dataset.src );
                        console.log("load page width "+lazyImage.width );
                        console.log("load page height "+lazyImage.height );
                        lazyImage.src = lazyImage.dataset.src+"?w="+lazyImage.width+"&h="+lazyImage.height;
                        console.log("loaded image "+lazyImage.src );
                        console.log("loaded real width "+lazyImage.naturalWidth );
                        console.log("loaded real height "+lazyImage.naturalHeight );
                        console.log("loaded page width "+lazyImage.width );
                        console.log("loaded page height "+lazyImage.height );
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
            lazyImages.forEach(function(lazyImage) {
                if (lazyImage.dataset.src==null){
                    lazyImage.dataset.src=lazyImage.src;
                    lazyImage.src='';
                }
                lazyImageObserver.observe(lazyImage);
            });
        }
    });

    $(document).ready(function() {
        lazyJsLoad("https://www.google-analytics.com/analytics.js");
        lazyJsLoad('https://www.google.com/recaptcha/api.js');
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga("create", "UA-6269913-1", "auto");
        ga("require", "ec");
        ga("require", "displayfeatures");
        ga("send", "pageview");
    });
    </script>



      <script type="text/javascript">
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