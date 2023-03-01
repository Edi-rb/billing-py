
function numberWithCommas(x) {
	return x.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

export default (original) => {
	let number = original || 0
	const rounded = Math.round(number*100)/100
	let decimailPart = (number - rounded).toFixed()
	return `$${numberWithCommas(rounded)}`	
}
