var upperChars = ["A","B","C","D","E","F","G","H","J","K","M","N","P","Q","R","S","T","U","V","W","X","Y","Z"];
var lowerChars = ["a","b","c","d","e","f","g","h","j","k","m","n","p","q","r","s","t","u","v","w","x","y","z"];
var numbers = ["2","3","4","5","6","7","8","9"];
var symbols = ["!","#","$","%","&","*","+","-","?","@"];
var similars_lower = ["i","l","o"];
var similars_upper = ["I","L","O"];
var similars_numbers = ["1","0"];
var similars_symbols = ["|"];
var ambiguous = ["\"","'","(",")",",",".","/",":",";","<","=",">","[","\\","]","^","_","`","{","}","~"];


function getPassword(passwordLength = 12)
{
    var chkIncludeLowerChar = true;
    var chkIncludeUpperChar = true;
    var chkIncludeNumbers = true;
    var chkIncludeSymbols = true;
    var chkExcludeSimilar = true;
    var chkExcludeAmbiguous = true;

    var password="";
    var array = [];
    var count = 0;
    if(chkIncludeLowerChar){
        array = array.concat(lowerChars);
    }
    if(chkIncludeUpperChar){
        array = array.concat(upperChars);
    }
    if(chkIncludeNumbers){
        array = array.concat(numbers);
    }
    if(chkIncludeSymbols){
       array = array.concat(symbols);
    }
    if(!chkExcludeSimilar){
        if(chkIncludeLowerChar)
        {
            array = array.concat(similars_lower);
        }
        if(chkIncludeUpperChar)
        {
            array = array.concat(similars_upper);
        }
        if(chkIncludeNumbers)
        {
            array = array.concat(similars_numbers);
        }
        if(chkIncludeSymbols)
        {
            array = array.concat(similars_symbols);
        }
    }
    if(!chkExcludeAmbiguous && chkIncludeSymbols){
        array = array.concat(ambiguous);
    }
    var randomIndex;
    if(array.length > 1)
    {
        for (var i = 0; i < passwordLength; i++) {
            randomIndex = Math.floor(Math.random() * array.length);
            password = password + array[randomIndex];
        }
    }
    return password;
}
