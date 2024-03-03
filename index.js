const header = document.querySelector('body > header');
// console.log(header.innerHTML);
let prevY = this.window.scrollY;

window.addEventListener('scroll', function () {
	if (prevY > this.window.scrollY)
		header.classList.remove('off');
	else
		header.classList.add('off');

	if (this.window.scrollY >= 200)
		header.classList.add('solid');
	else
		header.classList.remove('solid');
	// console.log(this.window.scrollY);
	prevY = window.scrollY;
});
