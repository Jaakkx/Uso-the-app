import React from "react";
import "../css/style.css";

class Banner extends React.Component{

    color = (previousColor:string) => {
        let color = ["#3DB372","#4A7DF2", "#F31EEB", "#FD7E40", "#F9B125"];

        console.log(Math.floor(Math.random() * (color.length )));
    }

    render(){
        return(
            <nav className="navbar">
                <div className="logo">
                    <a href="">USO</a>
                </div>
                <div className="nav-links" id="nav-links">
                    <ul>
                        <li>
                            <div className="link-background" onMouseEnter={()=>this.color("test")}>
                                <a href="" className="left">Menu</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        );
    }
}

export default Banner;