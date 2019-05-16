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