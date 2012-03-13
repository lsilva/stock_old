/******** JANELA POP-UP ********/
function ExitMe(){
	var conteudo = document.getElementById("content");
  var ExibirJanela = document.getElementById('ExibirJanela');
  conteudo.removeChild(ExibirJanela);
}
function ExibirJanela(titulo){
	var conteudo = document.getElementById("content");
	var referHeight = document.body.offsetHeight;
	var referWidth = document.body.offsetWidth;
	var newElement = document.createElement('div');
	newElement.setAttribute('id','ExibirJanela');
	//newElement.style.height=referHeight+'px';
	//newElement.style.width=referWidth+'px';
	//Titulo da Janela
	var tituloJanela = document.createElement('div');
	tituloJanela.setAttribute('id','tituloDiv');
	tituloJanela.appendChild(document.createTextNode(titulo));
	//Botão Sair
	var btnExit = document.createElement('div');
	btnExit.setAttribute('id','tituloExit');
	btnExit.setAttribute('onClick','ExitMe();');
	var conteudoDiv = document.createElement('div');
	conteudoDiv.setAttribute('id','conteudoDiv');
//	conteudoDiv.appendChild(document.createTextNode("Conteúdo da Janela de Pop-Up"));
	//Janela Interna
	var janelaChild = document.createElement('div');
	janelaChild.setAttribute('id','elementChild');
	janelaChild.appendChild(tituloJanela);
 	janelaChild.appendChild(btnExit);
 	janelaChild.appendChild(conteudoDiv);
	newElement.appendChild(janelaChild);
	conteudo.appendChild(newElement);
}
/******** JANELA POP-UP(FIM) ********/

