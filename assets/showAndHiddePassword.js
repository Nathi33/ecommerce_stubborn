document.addEventListener("DOMContentLoaded", () => {
  const passwordContainers = document.querySelectorAll(".password-container");

  passwordContainers.forEach((container) => {
    const eyeOn = container.querySelector(".eye-on");
    const eyeOff = container.querySelector(".eye-off");
    const inputPassword = container.querySelector("input");

    if (eyeOn && eyeOff && inputPassword) {
      eyeOn.addEventListener("click", () => {
        eyeOn.style.display = "none";
        eyeOff.style.display = "block";
        inputPassword.type = "text";
      });

      eyeOff.addEventListener("click", () => {
        eyeOff.style.display = "none";
        eyeOn.style.display = "block";
        inputPassword.type = "password";
      });
    }
  });
});
