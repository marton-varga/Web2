using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class UserRegisterWrapper : User
    {
        [Display(Name = "Jelszó mégegyszer")]
        public string Password2 { get; set; }
    }
}
