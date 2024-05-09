using Microsoft.AspNetCore.Http;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using System.Xml.Serialization;

namespace MovieWatcher.Models
{
    public class XmlHelper
    {
        public string Title { get; set; }
        public DateTime Added { get; set; }
        public XmlHelper() { }
        public XmlHelper(string title, DateTime added)
        {
            Title = title;
            Added = added;
        }
    }
}
