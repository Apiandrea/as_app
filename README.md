# Welcome to AS_APP
AS_APP is a website created with php, html, css, js and other languages representing a simple social network web application, where users can post a simple text with a title and a color where you can write whatever you want!
![UML](#UML)

## Design 
### Login Page
![login](img/login.png)

### Home Page
![home](img/home.png)

### About Us Page
![about_us](img/about_us.png)

## UML 

```mermaid
erDiagram
    USERS||--o{ POSTS : creates

    USERS {
        int id
        string username
        string email
        string password
        date created_at
        date updated_at
    }

		POSTS {
			int id
			int id_user
			string title
			string content
			string color
			date created_at
			date updated_at
		}

```
