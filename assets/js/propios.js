function WaitingOpen(texto){
	if(texto == '' || texto == null){
		$('#waitingText').html('Cargando ...');
	}
	else{
		$('#waitingText').html(texto);
	}
	$('#waiting').fadeIn('slow');
}

function WaitingClose(){
	$('#waiting').fadeOut('slow');
}

function LoadIconAction(idTag, action){
	var icon = "";
	var actt = "";

	switch(action){
		case	'Add':
			icon = '<i class="fa fa-fw fa-plus-square text-light-blue"></i>';
			actt = 'Agregar ';
			break;
		case 	'Edit':
			icon = '<i class="fa fa-fw fa-pencil text-light-blue"></i>';
			actt = 'Editar ';
			break;
		case 	'Del':
			icon = '<i class="fa fa-fw fa-times-circle text-light-blue"></i>';
			actt = 'Eliminar ';
			break;
		case 	'View':
			icon = '<i class="fa fa-fw fa-search text-light-blue"></i>';
			actt = 'Consultar ';
			break;
		case 	'Program':
			icon = '<i class="fa fa-fw fa-clock-o text-light-blue"></i>';
			actt = 'Programar ';
			break;
		case 	'ReProgram':
			icon = '<i class="fa fa-fw fa-clock-o text-light-blue"></i>';
			actt = 'Re-Programar ';
			break;
	}

	$('#'+idTag).html(icon + actt);
}


/* Devuelve Fecha Hora formateado para input date */
function getFechaHoraFormateada(date) {
	/* date es objeto fecha */
	var str = date.getFullYear() + "-" + getDosDigitos(date.getMonth()) + "-" + getDosDigitos(date.getDate()) + " " +  getDosDigitos(date.getHours()) + ":" + getDosDigitos(date.getMinutes()) + ":" + getDosDigitos(date.getSeconds());
	return str;
}
/* Devuelve Fecha Hora formateado para input date */
function getFechaFormateada(date) {
	/* date es objeto fecha */
	var str = date.getFullYear() + "-" + getDosDigitos(date.getMonth()) + "-" + getDosDigitos(date.getDate());
	return str;
}
/* Devuelve fecha con dos digitos */
function getDosDigitos(partTime) {
	if (partTime<10)
		return "0"+partTime;
	return partTime;
}

