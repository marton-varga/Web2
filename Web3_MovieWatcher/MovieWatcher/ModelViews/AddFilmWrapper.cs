using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class AddFilmWrapper
    {
        public int Id { get; set; } = 0;
        [Display(Name = "Cím")]
        public int UserId { get; set; }
        public string Title { get; set; }
        [Display(Name = "Év")]
        public int Year { get; set; }
        [Display(Name = "Műfaj")]
        public string Style { get; set; }
        [Display(Name = "Rendező")]
        public string Director { get; set; }
        [Display(Name = "Színészek")]
        public List<string> Actors { get; set;}
        [Display(Name = "Hozzáadva")]
        public DateTime Added { get; set; }
        [Display(Name = "Megnézve ekkor")]
        public DateTime? Watched { get; set; }
        [Display(Name = "Megnézve")]
        public bool IsWatched { get; set; }
    }
}
