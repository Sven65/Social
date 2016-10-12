var sequences = {
	konami: "↑ ↑ ↓ ↓ ← → ← → b a"
};

cheet(sequences.konami);

cheet.done(function(seq){
  switch(seq){
  	case sequences.konami:
  		$("#inp").val("Konami code :D");
  	break;
  }
});