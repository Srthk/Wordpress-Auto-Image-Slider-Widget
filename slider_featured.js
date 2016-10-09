var lString = document.getElementById('slider_links').innerHTML;
var iString = document.getElementById('slider_images').innerHTML;

var links = lString.split(",");
var images = iString.split(",");

var label = "";

for(var j = 0;j<images.length;j++){
var label = label + "<label class='dot' id='slider-img-" + j + "'></label>";
}

document.getElementsByClassName("dots")[0].innerHTML = label;
document.getElementById("slider-img-0").style.backgroundColor = "#DEDEDE";

        var i = 0;
        var reset = 0;
        var sliderLength = images.length - 1;
        
        var renew = setInterval(function(){
            if(links.length == i){
            
                i = 0;  
           }
            else{
            if(i!=0){
              document.getElementById("slider-img-" + --i).style.backgroundColor = "#4d4d4d";
              ++i;
            }
      else
        document.getElementById("slider-img-" + sliderLength).style.backgroundColor = "#4d4d4d";
            
            document.getElementById("slider-img-" + i).style.backgroundColor = "#DEDEDE";
            document.getElementById("bannerImage").src = images[i]; 
            document.getElementById("bannerLink").href = links[i];
            i++;
            }
        },5000);




