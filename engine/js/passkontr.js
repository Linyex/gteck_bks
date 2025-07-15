var T101 = "mc9ob8ob";
var T111 = "gaikts0";
var T201 = "4ecvbjus";
var T211 = "d2gvqhqv";
var T301 = "f83c509n";
var E101 = "htpfqxk4";
var E201 = "7qifnsnn";
var E301 = "vcmhd771";
var Y101 = "jaoqfkv7";
var Y201 = "ch3icxc8";
var Y301 = "1e5yw0bm";
var B101 = "vg9q0c2n";
var B201 = "ir7n6ffg";
var B301 = "bkjl0z45";

var pop = document.getElementById("pop");
var mpanel1 = document.getElementById("panel1");
var mpanel2 = document.getElementById("panel2");
var mpanel3 = document.getElementById("panel3");
var passgroup = [T101, T111, T201, T211, T301, E101, E201, E301, Y101, Y201, Y301, B101, B201, B301];

    function PassCheck(a) {
        var g = document.getElementById("pass");
        
        switch(g.value){ 
        
            case (passgroup[0]):
                    mpanel1.style.display = "block";
                
                var panelt101 = document.getElementById("panelt101");
                    panelt101.style.display = "block";
                    
                    pop.style.display = "none";
                alert("я долбаёб");
            break;
            
            case (passgroup[1]):
                mpanel1.style.display = "block";
                
                let panelt = document.getElementById("panelt111");
                    panelt.style.display = "block";
                
                pop.style.display = "none";
            break;

            default:
            alert(passgroup[0]);
            alert(passgroup);
            alert(g.value);
                alert("апщшоукп0ушпокцшщпоукшопукшщопишщукаопишщукопшщ");
            return false;
            }
        return false;
    }
