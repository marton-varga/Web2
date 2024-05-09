using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;

#nullable disable

namespace MovieWatcher.Models
{
    public partial class Searcher
    {
        public string keyWords { get; set; }
        public string filter { get; set; }
    }
}
