document.addEventListener("DOMContentLoaded", function(event) {

    for(let i = 1; i < 4; i++){

        if(document.querySelector('.products-'+i).getAttribute('data-count') > 2){
            let currentStart = 0;
            let currentEnd = 3;

            document.getElementById("swipe-up-"+i).onclick = function () {

                if(currentStart >= 0){

                    if(document.querySelector('.products-'+i+' [data-element-id="'+currentEnd+'"]') !== null){
                        document.querySelector('.products-'+i+' [data-element-id="'+currentStart+'"]').style.display = 'none';
                        document.querySelector('.products-'+i+' [data-element-id="'+currentEnd+'"]').style.display = 'flex';

                        currentStart++;
                        currentEnd++;
                    }
                }
            };

            document.getElementById("swipe-down-"+i).onclick = function () {

                if(currentStart > 0) {
                    currentStart--;
                    currentEnd--;

                    document.querySelector('.products-'+i+' [data-element-id="' + currentStart + '"]').style.display = 'flex';
                    document.querySelector('.products-'+i+' [data-element-id="' + currentEnd + '"]').style.display = 'none';
                }

            };
        }
    }
});