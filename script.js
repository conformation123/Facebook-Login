document.addEventListener("DOMContentLoaded", function () {
  const mobileInput = document.getElementById("mobile");
  const mobileError = document.getElementById("mobileError");
  const saveMobileBtn = document.getElementById("saveMobile");
  const otpInput = document.getElementById("otp");
  const otpError = document.getElementById("otpError");
  const saveOtpBtn = document.getElementById("saveOtp");

  if (mobileInput) {
    mobileInput.addEventListener("input", function () {
      const mobile = mobileInput.value.trim();
      const mobileRegex = /^[6-9]\d{9}$/;

      if (mobile.length === 0) {
        mobileError.textContent = "";
        saveMobileBtn.disabled = true;
      } else if (!mobileRegex.test(mobile)) {
        mobileError.textContent = "Invalid mobile number!";
        saveMobileBtn.disabled = true;
      } else {
        mobileError.textContent = "";
        saveMobileBtn.disabled = false;
      }
    });
  }

  if (otpInput) {
    otpInput.addEventListener("input", function () {
      const otp = otpInput.value.trim();
      const otpRegex = /^\d{4,8}$/;

      if (!otpRegex.test(otp)) {
        otpError.textContent = "OTP must be 4 to 8 digits.";
        saveOtpBtn.disabled = true;
      } else {
        otpError.textContent = "";
        saveOtpBtn.disabled = false;
      }
    });
  }
});
