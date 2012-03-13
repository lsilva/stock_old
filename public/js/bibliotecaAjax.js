var ajax;
var dadosUsuario;

//Trabalhar com apenas arquivos
//requisicaoHTTP('post',url,true);

//Trabalhar com forms
//enviaDados(url);

// ------- cria o objeto e faz a requisição -------
function requisicaoHTTP(tipo,url,assinc){
	if(window.XMLHttpRequest){	  // Mozilla, Safari,...
		ajax = new XMLHttpRequest();
	} 
	else if (window.ActiveXObject){	// IE
		ajax = new ActiveXObject("Msxml2.XMLHTTP");
		if (!ajax) {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
    }
	if(ajax)	// iniciou com sucesso
		iniciaRequisicao(tipo,url,assinc);
	else
		alert("Seu navegador não possui suporte a essa aplicação!");
}

// ------- Inicializa o objeto criado e envia os dados (se existirem) -------
function iniciaRequisicao(tipo,url,bool){
	ajax.onreadystatechange=trataResposta;
	ajax.open(tipo,url,bool);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
	//ajax.overrideMimeType("text/XML");   /* usado somente no Mozilla */
	ajax.send(dadosUsuario);
}


// ------- EX: Inicia requisição com envio de dados -------
function enviaDados(form,url){
	criaQueryString(form);
	requisicaoHTTP("POST",url,true);
}


// ------- Cria a string a ser enviada, formato campo1=valor1&campo2=valor2... -------
function criaQueryString(form){
	dadosUsuario="";
	var frm = form;
	if(!frm);
	for(var i = 0; i < form.elements.length; i++)  {
	  	if(frm.elements[i].getAttribute('type')=='radio'){			//Adicionado pelo Luis: Se for campo
	    	if(frm.elements[i].checked==true)                     //radio armazena "apenas" o campo selecionado;
				dadosUsuario += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value)+"&";
	    }else if(frm.elements[i].getAttribute('type')=='checkbox'){   //Adicionado pelo Luis: Se for campo checkbox
	    	if(frm.elements[i].checked==true)                           //armazena 1 se estiver selecionado e 0 caso contrário;
				dadosUsuario += frm.elements[i].name+"="+'1'+"&";
	    	else
				dadosUsuario += frm.elements[i].name+"="+'0'+"&";
		}else{
			dadosUsuario += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value)+"&";
	    }
	}
  //Retira o & que há no último caracter;
  dadosUsuario=dadosUsuario.substr(0,dadosUsuario.length-1);
}

// ------- Trata a resposta do servidor -------
function trataResposta(){
	if(ajax.readyState == 4){
		//loading(false);
		if(ajax.status == 200){
			trataDados();  // criar essa função no seu programa
		} else {
			alert("Problema na comunicação com o objeto XMLHttpRequest. "+ajax.status );
		}
	}
}

 //<


// Utilizado para criar o efeito de loading
/*function loading(opt) {
	if (opt == true) {
		var conteudo = document.getElementsByTagName('body')[0];
		var newElement = document.createElement('div');
		newElement.setAttribute('id','ExibirJanela');
		var referHeight = window.innerHeight;
 		var referWidth = window.innerWidth;
		newElement.style.height=referHeight+'px';
 		newElement.style.width=referWidth+'px';
	  //Cria Imagem
		var img = document.createElement('img');
		img.setAttribute('src','/zf/acme_/public/images/carregando2.gif');
		img.setAttribute('id','loading');
		// Dizemos que o margin-top será a metada do tamanho da div
		img.style.marginTop = ((referHeight-25)/2) + 'px';
		img.style.marginLeft = ((referWidth-50)/2) + 'px';
		// Evita que seja criada duas ou mais img de loading
		if (!document.getElementById('loading')) {
	    newElement.appendChild(img);
			conteudo.appendChild(newElement);
		}
	} else if (opt == false) {
		// Referenciamos a img de login através de seu ID
		var imgLoading = document.getElementById("ExibirJanela");
		// Removemos a img de loading
		if (imgLoading) {
			imgLoading.parentNode.removeChild(imgLoading);
		}
	}
}*/
