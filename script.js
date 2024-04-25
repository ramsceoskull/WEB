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


const handleChangeReview = () => {
	for (let item of document.querySelectorAll(".review")) {
		item.classList.toggle("hidden");
	}
	document.querySelector(".fa-angle-right").classList.toggle("invisible");
	document.querySelector(".fa-angle-left").classList.toggle("invisible");
};

const mobileReviewContainer = () => {
	document.querySelector(".fa-angle-left").classList.remove("hidden");
	document.querySelector(".fa-angle-right").classList.remove("hidden");
	document.querySelector(".fa-angle-left").classList.add("invisible");
	document.querySelector(".fa-angle-right").classList.remove("invisible");
	document.getElementById("1").classList.remove("hidden");
	// document.getElementById("2").classList.add("hidden");
	document.getElementById("3").classList.add("hidden");

	// let plan = document.getElementsByClassName("plans-card")[1];
	// plan.scrollIntoView({ inline: "center" });
};

const tabletReviewContainer = () => {
	document.querySelector(".fa-angle-right").classList.add("invisible");
	document.querySelector(".fa-angle-left").classList.add("invisible");
	document.getElementById("1").classList.remove("hidden");
	// document.getElementById("2").classList.remove("hidden");
	document.getElementById("3").classList.remove("hidden");
};

window.addEventListener("load", () => {
	const media = window.matchMedia(`(min-width: 768px)`);

	media.addEventListener("change", (e) => {
		if (e.matches) {
			tabletReviewContainer();
		} else {
			mobileReviewContainer();
		}
	});

	if (media.matches) {
		tabletReviewContainer();
	} else {
		mobileReviewContainer();
	}
});
