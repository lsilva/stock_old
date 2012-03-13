function submitPedidoEntrada(frm){
	if (rowTable_Counter>1){
//	  url = frm.getAttribute('action');
	// 	enviaDados(url);
//		alert('Número de itens no carrinho = '+(rowTable_Counter-1));
		ativaAjax("/zf/acme_/entrada/fecharpedido");
  }else{
  	alert('Não há itens no pedido para ser adicionado.');
  }
}

function afetaCustos(option){
 	var tipomov_sai = document.getElementById('tipomov_tipo-S');
 	var tipomov_ent = document.getElementById('tipomov_tipo-E');
 	var tipomov_est = document.getElementById('tipomov_estorno');
  var ativar=false;
//  tipomov_est.checked  F/T
	if((tipomov_ent.checked==true && tipomov_est.checked==false)||(tipomov_sai.checked==true && tipomov_est.checked==true)){
  	ativar=true;
  }
  document.getElementById('tipomov_customedio').checked=ativar;
  document.getElementById('tipomov_customedio').disabled=!ativar;
  document.getElementById('tipomov_custoatual').checked=ativar;
  document.getElementById('tipomov_custoatual').disabled=!ativar;
}

function limparMenu() {
 	var menu = document.getElementById('menu');
	var linksBtn = menu.getElementsByTagName("a");
	for (var i=0; i<linksBtn.length; i++) {
    var _link = linksBtn[i];
    if(!_link){return false;}
    linksBtn[i].style.color='#EEE';
    linksBtn[i].style.background='#00473F';
	}
}

function linksMenu(){
 	var menu = document.getElementById('menu');
  var linksBtn = menu.getElementsByTagName('a');
  for (var x = 0; x < linksBtn.length; x++) {
    var _link = linksBtn[x];
    if(!_link){return false;}
    _link.onclick = function() {
    	limparMenu();
      this.style.color='#FFA500';
      var pai=this.parentNode.parentNode.parentNode;
      pai.firstChild.style.color='#FFA500';
      this.style.background='#527574';
      pai.firstChild.style.background='#527574';
    }
  }
}

function linksContent(content){
	var recipiente=document.getElementById(content);
  var linksBtn = recipiente.getElementsByTagName('a');
  for (var x = 0; x < linksBtn.length; x++) {
    var _link = linksBtn[x];
    if(!_link){return false;}
    _link.onclick = function() {
	    if(this.getAttribute('id')){
		    if(this.getAttribute('id')=='excluir'){
		    	resposta = confirm("Deseja realmente excluir o registro?");
          if(resposta==true){
          	return ativaAjax(this.getAttribute('href'));
          }
		    	return resposta;
/*        }else if(this.getAttribute('id')=='adicionar'){
        	return ativaAjax(this.getAttribute('href')); */
        }else{
	 	      return ativaAjax(this.getAttribute('href'));
        }
	    }
      if(content=='menu_geral' && this.getAttribute('href')!='#' && this.getAttribute('class')!='menu'){
				return ativaAjax(this.getAttribute('href'));
      }
    }
  }
}

/******** VALIDA FORMULÁRIO ********/
  function verificaForm(form){
    var frm = form;
    if(!frm)return false;
    for (var i = 0; i < frm.elements.length; i++){
      if ( (frm.elements[i].title.substr(0,1)) == "*" ){
        if (frm.elements[i].value == ""){
          alert("O campo '"+frm.elements[i].title.substr(1,(frm.elements[i].title.length))+"' é de preenchimento obrigatório!");
          frm.elements[i].style.backgroundColor = "#ffffcc";
          frm.elements[i].focus();
          return false;
          break;
        }else{
          frm.elements[i].style.backgroundColor = "#ffffff";
        }
      }
    }
    return true;
  }
/******** VALIDA FORMULÁRIO(FIM) ********/

/************ FUNCÇÕES AJAX ************/
var url;
function validaForm(form,target){
alert(form);
	if(verificaForm(form) || typeof(target)!='undefined'){
	  if(form){
		  url = form.getAttribute('action');
	  }else{
	    url = target;
      var form;
    }
	 	enviaDados(form,url);
	}
  return false;
}

function validaFormTransaction(form){
	if(verificaForm(form)){
	  url = form.getAttribute('action');
    for (var i = 0; i < form.elements.length; i++){
    	var id = form.elements[i].getAttribute('id');
			if(id=='caixa_data'){
      	var data=form.elements[i].value.split("/");
        var dataInput = data[2]+""+data[1]+""+data[0];
        var dataNow = new Date();
        var dia = dataNow.getDate();
        dia = (dia<=9)?"0"+dia:dia;
        var mes = dataNow.getMonth()+1;
        mes = (mes<=9)?"0"+mes:mes;
        dataNow=dataNow.getFullYear()+""+mes+""+dia;
	      if(dataInput<dataNow){
        	alert("A data escolhida é menor que a data atual.");
          return false;
        }
      }else if(id=='chkIndef'){
      	if(form.elements[i].checked==true)  //Coloca o valor para parcelas infinitas
        	form.elements[i-2].value=88;      //na caixa de texto caixa_repetir_vezes
      }else if(id=='chkRepetir'){
	     	if(form.elements[i].checked==false){
			    repetirTransaction(form.elements[i],true);
        }
      }
    }
	 	enviaDados(form,url);
	}
  return false;
}

function validaFormEntrada(form){
	if(verificaForm(form)){
	  url = form.getAttribute('action');
    for (var i = 0; i < form.elements.length; i++){
    	var id = form.elements[i].getAttribute('id');
			if(id=='produto_preco'){
     		var nro = form.elements[i].value.replace(/\D/g,"");
        if(parseInt(nro)<1){
        	alert('O "Valor Unitário" não pode se menor que R$ 1,00.');
	        return false;
        }
      }else if(id=='produto_qtd'){
				var nro = form.elements[i].value.replace(/\D/g,"");
        if(parseInt(nro)<1){
        	alert('O "Quantidade" não pode se menor que 1.');
	        return false;
        }
      }
    }
	 	enviaDados(form,url);
	}
  return false;
}

function ativaAjax(elem){
  url = elem;
 	requisicaoHTTP('GET',url,true);
  return false;
}

function trataDados(){
  info =  ajax.responseText;
  var content='pg-content';
  var tamanhoTable='98%';
	//Array contendo os valores para ordernar as tabelas
  var arrOrdem = new Array('N','S','S',false,false);
  if(url.indexOf("?")!=-1){
		url=url.substr(0,url.indexOf("?"));
  }
  switch(url){
  	case "/zf/acme_/ajax/produtoslistar":
    	ExibirJanela('Lista de Produtos');
      content='conteudoDiv';
      tamanhoTable='800';
    break;
   	case "/zf/acme_/ajax/produtos":
//    alert(ajax.responseText);
			content='';
   	  info = ajax.responseXML;
      var itens = info.getElementsByTagName('item');
      document.getElementById('produto_desc').value=itens[0].getAttribute("label");
      document.getElementById('produto_id').value=itens[0].getAttribute("id");
      var precoProd;
      if(itens[0].getAttribute("produto_custoatual")){
	      precoProd=itens[0].getAttribute("produto_custoatual");
				document.getElementById('produto_estoque').value=itens[0].getAttribute("produto_estoque");
      }else{
      	precoProd=itens[0].getAttribute("produto_preco");
      }

      document.getElementById('produto_preco').value=precoProd;
//

			//Se o produto jah estiver no carrinho deve carregar o desconto/preco cadastrado anteriormente
      if(itens[0].getAttribute("desc_unit")){
        document.getElementById('produto_desconto').value=itens[0].getAttribute("desc_unit");
        document.getElementById('produto_desconto').disabled=true;
        document.getElementById('produto_preco').value=precoProd;
        document.getElementById('produto_preco').disabled=true;
	      document.getElementById('produto_qtd').focus();
      }else{
        document.getElementById('produto_desconto').disabled=false;
        document.getElementById('produto_desconto').value="0,00";
        if(itens[0].getAttribute("produto_custoatual")){
	        document.getElementById('produto_preco').disabled=false;
	        document.getElementById('produto_preco').value="0,00";
	        document.getElementById('produto_preco').focus();
        }
      }
      if(itens[0].getAttribute("produto_custoatual"))
      	calculaTotal();
      ExitMe();
    break;
   	case "/zf/acme_/entrada/excluir":
      content='tabela_ordena';
      tamanhoTable='700';
      arrOrdem=["N", "S", "N","N","N","N",false];
      //Se não tiver nenhum item no carrinho desbloquear o Combo do Fornecedor
    	if (rowTable_Counter<=2){
				document.getElementById('forne_id').options[0].selected=true;
				document.getElementById('forne_id').removeAttribute('disabled');
        document.getElementById('historico_documento').value='';
				document.getElementById('historico_documento').removeAttribute('disabled');
      }
    break;
   	case "/zf/acme_/entrada/fecharpedido":
    	ExibirJanela('Fechar Pedido');
			content='conteudoDiv';
    break;
  	case "/zf/acme_/ajax/produtoslistar":
//    	ExitMe();
    break;
   	case "/zf/acme_/entrada/comprar":
   	case "/zf/acme_/entrada/form":
      content='pg-content';
      tamanhoTable='700';
      arrOrdem=["N", "S", "N","N","N","N",false];
    break;
   	case "/zf/acme_/transaction/update":
   	case "/zf/acme_/transaction/excluir":
      arrOrdem=[false, "S", false,false];
    break;
  }
  document.getElementById(content).innerHTML=info;
  if(url.indexOf('transaction/form')!=-1)
  	ativaRepetirIndef();
  linksContent(content);
  initTableWidget('myTable',tamanhoTable,'430',arrOrdem);
}
/********* FUNCÇÕES AJAX(FIM) *********/
/******** JANELA POP-UP ********/
function ExitMe(){
	var conteudo = document.getElementById("pg-body");
  var ExibirJanela = document.getElementById('ExibirJanela');
  conteudo.removeChild(ExibirJanela);
}
function ExibirJanela(titulo){
	var conteudo = document.getElementById("pg-body");
	var referHeight = document.body.offsetHeight;
	var referWidth = document.body.offsetWidth;
	var newElement = document.createElement('div');
	newElement.setAttribute('id','ExibirJanela');
	newElement.style.height=referHeight+'px';
	newElement.style.width=referWidth+'px';
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

/******** ENTRADA-FORM ********/
function navegaPg(obj){
	ativaAjax('/zf/acme_/ajax/produtos?id='+obj.value);
	return false;
}
function Monitora(obj,e){
	var tecla = (e.keyCode) ? e.keyCode : e.which;
	if(tecla==13){
		navegaPg(obj);
		return false;
	}
}
/******** ENTRADA-FORM(FIM) ********/
//Calcula o campo total do form da pagina entrada/form
function calculaTotal(){
	var total = (document.getElementById('produto_preco').value.replace(/\D/g,"")-
  document.getElementById('produto_desconto').value.replace(/\D/g,""))*
  document.getElementById('produto_qtd').value.replace(/\D/g,"");
  document.getElementById('historico_valor_total').value=total;
  mascara(document.getElementById('historico_valor_total'),Moeda);
}
//Habilita/Desabilita campos da página transaction/form
function repetirTransaction(check,apaga){
	var TRepetir = document.getElementById('TRepetir');
  if(check.checked==true && TRepetir.style.display=='none'){
  	TRepetir.style.display='block';
    if(apaga!=true)return;
    document.getElementById('caixa_repetir_vezes').setAttribute('title','*');
  	document.getElementById('caixa_repetir_vezes').disabled=false;
    document.getElementById('caixa_repetir_tipo').disabled=false;
    document.getElementById('caixa_parcela_inicio').disabled=false;
    document.getElementById('caixa_parcela_qtd').disabled=false;
  }else{
  	TRepetir.style.display='none';
    if(apaga!=true)return;
    document.getElementById('caixa_repetir_vezes').value='';
    document.getElementById('caixa_repetir_tipo').options[0].selected=true;
    document.getElementById('chkIndef').checked=false;
    document.getElementById('caixa_parcela_inicio').value='';
    document.getElementById('caixa_parcela_qtd').value='';
    document.getElementById('caixa_repetir_vezes').setAttribute('title','');
  }
}
//CheckBox Repetir Indefinidamente campos da página transaction/form
function repetirIndef(check){
	if(check.checked==true){
  	document.getElementById('caixa_repetir_vezes').disabled=true;
    document.getElementById('caixa_repetir_tipo').disabled=true;
    document.getElementById('caixa_parcela_inicio').disabled=true;
    document.getElementById('caixa_parcela_qtd').disabled=true;
		document.getElementById('caixa_repetir_vezes').setAttribute('title','');
  }else{
  	document.getElementById('caixa_repetir_vezes').disabled=false;
    document.getElementById('caixa_repetir_tipo').disabled=false;
    document.getElementById('caixa_parcela_inicio').disabled=false;
    document.getElementById('caixa_parcela_qtd').disabled=false;
  	document.getElementById('caixa_repetir_vezes').setAttribute('title','*');
  }
}
//CheckBox Repetir Indefinidamente campos da página transaction/form
function ativaRepetirIndef(){
  var chkRepetir = document.getElementById('chkRepetir');
	if(document.getElementById('caixa_repetir_vezes').value==88){
    document.getElementById('caixa_repetir_vezes').value='';
    chkRepetir.checked=true;
    repetirTransaction(chkRepetir,true);
    var chkIndef = document.getElementById('chkIndef');
    chkIndef.checked=true;
    repetirIndef(chkIndef);
  }else if(document.getElementById('caixa_repetir_vezes').value>=1){
    chkRepetir.checked=true;
    repetirTransaction(chkRepetir);
  }else if(document.getElementById('caixa_repetir_vezes').value==0){
    chkRepetir.checked=false;
    repetirTransaction(chkRepetir,true);
  }
}

//Mascara de Entrada
function mascara(o,f){
  v_obj=o
  v_fun=f
	setTimeout("execmascara()",1)
}
function execmascara(){
  v_obj.value=v_fun(v_obj.value)
}

//Input monetária do inputbox
function FormatNumber(input,max){
  var text=input;
  text.style.textAlign='right';
  text.setAttribute('maxlength',max);
	text.onkeydown = function(oEvent) {
		this.selectionStart = this.value.length;	//Posicionar o cursor no inicio do campo
		this.selectionEnd = this.value.length;    //Posicionar o cursor no inicio do campo
    oEvent = oEvent || window.event;// Fix para IE
    var iKeyCode = oEvent.keyCode;// Código ASCII da tecla pressionada
    // Checagem se números || backspace || TAB || enter
		if((iKeyCode>=48 && iKeyCode<=57)||(iKeyCode>=96 && iKeyCode<=105)|| iKeyCode == 8 || iKeyCode == 9 || iKeyCode == 13){
     	mascara(this,Moeda);
    }else{
    	return false;
    }
	}
}

//Mascara de entrada monetária do inputbox
function Moeda(v){
	var str=v;
	str=str.replace(/\D/g,"");
	if(str.length<3)str=str_repeat(0,3-str.length)+str;
	var dec=str.substr(str.length-2);
	var inteiro=str.substring(0,str.length-2);
	for(var i=0;i<inteiro.length;i++){
	  var unit = inteiro.substr(i,1);
	  if(unit!=0 || i==inteiro.length-1){
	    inteiro=inteiro.substr(i,inteiro.length);
	    break;
	  }
	}
	if(inteiro.length>3){
	  var cent=inteiro.substr(str.length-5);
	  var mil=inteiro.substr(0,str.length-5);
	  inteiro=mil+"."+cent;
	}
	var number = inteiro+","+dec;
	return number;
}
//Repetir caracteres
function str_repeat (input,multiplier) {
	//@input 			= Caractere para repetir
  //@multiplier = Número de vezes para repetir
  return new Array(multiplier+1).join(input);
}

/******* Fechar Pedido (entrada/fecharpedido)**********/
function CancelarPedido(){
 	resposta = confirm("Deseja realmente cancelar o pedido?");
  if(resposta==true){
		ExitMe();
		document.getElementById('forne_id').options[0].selected=true;
		document.getElementById('forne_id').removeAttribute('disabled');
    document.getElementById('historico_documento').value='';
		document.getElementById('historico_documento').removeAttribute('disabled');
	 	ativaAjax("/zf/acme_/entrada/cancelar");
  }
 	return resposta;
}

function loadFunctions(){
  linksMenu();
  linksContent('pg-content');
  var menu_geral = document.getElementById('menu_geral');
  if(menu_geral){
    linksContent('menu_geral');
  }
}

window.onload=function() {loadFunctions();}



