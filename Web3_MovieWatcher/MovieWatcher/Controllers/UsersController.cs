using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http;
using MovieWatcher.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace MovieWatcher.Controllers
{
    public class UsersController : Controller
    {
        public IActionResult Login(User user)
        {
            return View();
        }
        [HttpPost]
        public IActionResult LogInPost(User user)
        {
            string uname = UsersService.GetUserEmail(user);
            if(uname != null)
            {
                HttpContext.Session.SetString("uname", uname);
                return View();
            }
            else
            {
                ViewData["loginError"] = "Hibás felhasználónév vagy jelszó!";
                return View("Login", user);
            }   
        }

        public IActionResult Register()
        {
            return View();
        }
        [HttpPost]
        public IActionResult RegisterPost(UserRegisterWrapper urw)
        {
            bool[] errors = UsersService.RegisterValidation(urw);
            if (!errors.Contains(true))
            {
                UsersService.SaveUser(urw);
                HttpContext.Session.SetString("uname", urw.Name);
                return View();
            }
            else
            {
                ViewData["registerErrors"] = errors;
                return View("Register");
            }
        }

        public IActionResult LogOut()
        {
            HttpContext.Session.Remove("uname");
            ViewData["uname"] = null;
            return View();
        }
    }
}
