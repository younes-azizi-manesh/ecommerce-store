@extends('auth.layouts.master-auth')

@section('head-tag')
    <title>تایید کد</title>
    <style>
        .verify-form {
            max-width: 400px;
            width: 90%;
            padding: 20px;
            margin: auto;
        }
        .verify-form .form-group {
            margin-bottom: 1rem;
        }
        .verify-form .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }
        .verify-form .otp-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        .verify-form .btn-primary {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .verify-form .text-center {
            text-align: center;
        }
        .verify-form .alert_required {
            display: block;
            margin-top: 0.5rem;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .verify-form {
                padding: 15px;
            }
            .verify-form h1 {
                font-size: 18px;
            }
            .verify-form .otp-input {
                padding: 8px;
                font-size: 14px;
            }
            .verify-form .btn-primary {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .verify-form {
                width: 100%;
                padding: 10px;
            }
            .verify-form h1 {
                font-size: 16px;
            }
            .verify-form .form-group label {
                font-size: 12px;
            }
            .verify-form .otp-input {
                font-size: 12px;
            }
            .verify-form .btn-primary {
                font-size: 14px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="flex min-h-screen items-center justify-center bg-background">
        <div class="verify-form">
            <div class="relative mx-auto rounded-xl bg-muted p-5 shadow-base">
                <!-- Return -->
                <div>
                    <a href="{{route('auth.login-register-form', $token)}}" id="" class="py-3" style="max-width: 70px; max-height:30px;font-size:12px">
                        بازگشت
                    </a>
                </div>
                <!-- Logo -->
                <a href="{{route('home')}}">
                    <img src="" class="mx-auto mb-5 w-40" alt="" />
                </a>
                <h1 class="mb-5 text-lg">کد تایید را وارد کنید</h1>
                <div class="mb-4 space-y-4">
                    <form id="otp" method="POST" action="{{ route('auth.login-confirm', $token) }}" class="flex items-center justify-between gap-x-2">
                        @csrf
                        <input type="text" name="otp" class="otp-input h-14 w-14 rounded-lg border bg-muted text-center text-lg outline-none xs:h-16 xs:w-16 md:text-xl" />
                        @error('otp')
                        <span class="alert_required bg-danger text-white p-1 rounded" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </form>
                    <p class="h-5 text-sm text-red-500 dark:text-red-400">
                    </p>
                </div>
                <ul class="mb-8 space-y-4">
                    <li>
                        <p class="text-primary text-sm" id="countdownSection">
                            زمان باقی‌مانده تا ارسال مجدد
                            <span id="countdown" class="font-bold">2:00</span>
                        </p>
                        <a href="{{route('auth.login-resend-otp', $token)}}" id="resendButton" onclick="" class="!hidden btn-primary-nobg text-sm w-fit">
                            <span> ارسال مجدد </span>
                            <span>
                                <svg class="h-5 w-5">
                                    <use xlink:href="#chevron-left" />
                                </svg>
                            </span>
                        </a>
                    </li>
                </ul>
                <div>
                    <button form="otp" type="submit" id="verify-btn" class="btn-primary w-full py-3">
                        تایید
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Time for Resend OTP , Current : 2 minutes
    let countdownMinutes = 2;
    let countdownSeconds = 0;
    let countdownInterval;

    function updateCountdown() {
      const countdownElement = document.getElementById("countdown");
      countdownElement.textContent = `${countdownMinutes}:${countdownSeconds
        .toString()
        .padStart(2, "0")}`;
    }

    function startCountdown() {
      countdownInterval = setInterval(function () {
        if (countdownMinutes === 0 && countdownSeconds === 0) {
          clearInterval(countdownInterval);
          document.getElementById("resendButton").classList.remove("!hidden");
          document.getElementById("resendButton").disabled = false;
          document.getElementById("countdownSection").classList.add("hidden");
        } else {
          if (countdownSeconds === 0) {
            countdownMinutes--;
            countdownSeconds = 59;
          } else {
            countdownSeconds--;
          }
          updateCountdown();
        }
      }, 1000);
    }

    function resendOTP() {
      if (countdownMinutes != 0 || countdownSeconds != 0) return;
      // Add your OTP resend logic here
      // For demonstration, let's just reset the countdown and disable the button
      countdownMinutes = 2;
      countdownSeconds = 0;
      updateCountdown();
      document.getElementById("resendButton").classList.add("!hidden");
      document.getElementById("resendButton").disabled = true;
      document.getElementById("countdownSection").classList.remove("hidden");

      startCountdown();
    }

    startCountdown(); // Start the countdown when the page loads

    // Otp Input Section Start
    const form = document.querySelector("#otp-form");
    const inputs = document.querySelectorAll(".otp-input");
    const verifyBtn = document.querySelector("#verify-btn");

    inputs[0].focus();

    const isAllInputFilled = () => {
      return Array.from(inputs).every((item) => item.value);
    };

    const getOtpText = () => {
      let text = "";
      inputs.forEach((input) => {
        text += input.value;
      });
      return text;
    };

    const verifyOTP = () => {
      if (isAllInputFilled()) {
        const text = getOtpText();
        alert(`Your OTP is "${text}". OTP is correct`);
      }
    };

    const toggleFilledClass = (field) => {
      if (field.value) {
        field.classList.add("!border-emerald-500");
        field.classList.add("!border-emerald-400");
      } else {
        field.classList.remove("!border-emerald-500");
        field.classList.remove("!border-emerald-400");
      }
    };
    form.addEventListener("input", (e) => {
      const target = e.target;
      const value = target.value;

      toggleFilledClass(target);
      if (target.nextElementSibling) {
        target.nextElementSibling.focus();
      }
      verifyOTP();
    });
    inputs.forEach((input, currentIndex) => {
      // fill check
      toggleFilledClass(input);
      // paste event
      input.addEventListener("paste", (e) => {
        e.preventDefault();
        const text = e.clipboardData.getData("text");

        inputs.forEach((item, index) => {
          if (index >= currentIndex && text[index - currentIndex]) {
            item.focus();
            item.value = text[index - currentIndex] || "";
            toggleFilledClass(item);
            verifyOTP();
          }
        });
      });
      // backspace event
      input.addEventListener("keydown", (e) => {
        if (e.keyCode === 8) {
          e.preventDefault();
          input.value = "";
          // console.log(input.value);
          toggleFilledClass(input);
          if (input.previousElementSibling) {
            input.previousElementSibling.focus();
          }
        } else {
          if (input.value && input.nextElementSibling) {
            input.nextElementSibling.focus();
          }
        }
      });
    });
    verifyBtn.addEventListener("click", () => {
      verifyOTP();
    });
    // Otp Input Section End
  </script>
@endsection