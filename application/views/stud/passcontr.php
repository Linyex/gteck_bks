<script type="text/javascript">
var T101 = "mc9ob8ob";
var T111 = "gaikts0";
var E101 = "htpfqxk4";
var Y101 = "jaoqfkv7";

var lekc = document.getElementById("lekcii");
var pop = document.getElementById("pop");
var mpanel1 = document.getElementById("panel1");
var passgroup = [T101, T111, E101, Y101];

    function PassCheck(a) {
        var g = document.getElementById("pass");

        switch(g.value){ 
        
            case (passgroup[0]):
                alert(mpanel1);
                alert(g);
                    mpanel1.style.display = "block";
                    alert("Я тут1");
                var panelt101 = document.getElementById("panelt101");
                    panelt101.style.display = "inline";
                    alert("Я тут2");
                    lekc.style.display = "block";
                    alert("Я тут3");
                    pop.style.display = "none";
                alert("я снова тут");
                return false;
            break;
            
            case (passgroup[1]):
                    mpanel1.style.display = "block";
                
                var panelt111 = document.getElementById("panelt111");
                    panelt111.style.display = "inline";

                    lekc.style.display = "block";
                    
                    pop.style.display = "none";
                return false;
            break;

            case (passgroup[2]):
                    mpanel1.style.display = "block";
                
                var panele101 = document.getElementById("panele101");
                    panele101.style.display = "inline";
                
                    lekc.style.display = "block";
                    
                    pop.style.display = "none";
                return false;
            break;

            case (passgroup[3]):
                    mpanel1.style.display = "block";
                
                var panely101 = document.getElementById("panely101");
                    panely101.style.display = "inline";
                    
                    lekc.style.display = "block";
                    
                    pop.style.display = "none";
                return false;
            break;

            default:
                alert("Неправильный пароль");
            return false;
            }
        return false;
    }

</script>