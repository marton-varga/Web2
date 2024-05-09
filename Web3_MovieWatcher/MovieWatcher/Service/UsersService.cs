using Microsoft.AspNetCore.Http;

#nullable disable

namespace MovieWatcher.Models
{
    public static class UsersService
    {
        public static User GetUserByEmail(string email)
        {
            Database db = new Database();
            foreach (User u in db.Users)
            {
                if (u.Email == email)
                {
                    return u;
                }
            }
            return null;
        }
        public static string GetUserEmail(User user)
        {
            Database db = new Database();
            foreach (User u in db.Users)
            {
                if (u.Email == user.Email && u.Password == user.Password)
                {
                    return user.Email;
                }
            }
            return null;
        }


        public static User SaveUser(UserRegisterWrapper urw)
        {
            Database db = new Database();
            User user = (User)urw;
            db.Users.Add(user);
            db.SaveChanges();
            return user;
        }
        public static bool[] RegisterValidation(UserRegisterWrapper urw)
        {
            //0: UserNameExists
            //1: EmailExists
            //2: PasswordsDontMatch
            bool[] errors = { false, false, false };

            Database db = new Database();
            foreach (User u in db.Users)
            {

                if (u.UserName == urw.UserName)
                {
                    errors[0] = true;
                }
                if (u.Email == urw.Email)
                {
                    errors[1] = true;
                }
            }
            if (urw.Password != urw.Password2)
            {
                errors[2] = true;
            }
            return errors;
        }
    }
}
