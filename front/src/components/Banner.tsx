import React from "react";
import { ReactComponent as Logo } from '../assets/uso.svg';
import menu from '../assets/menu.svg';
import { Link } from "react-router-dom";

class Banner extends React.Component{
    state={
        hovered:false,
        color:"vert",
        oldColor:0,
    }

    color = () => {
        let color = ["vert","bleu", "rose", "orange", "jaune"];
        let colorValue = Math.floor(Math.random() * (color.length ));

        if(this.state.hovered){
            if(colorValue == this.state.oldColor){
                colorValue = Math.floor(Math.random() * (color.length ));
            }else{
                this.setState({oldColor:colorValue});
                this.setState({color : color[colorValue]});
            }
        }
    }

    onMouseEnter = () => {
        this.setState({ hovered: true });
        // this.color();
    };
    
    onMouseLeave = () => {
        this.setState({ hovered: false });
        this.color();
    };

    render(){
        return(
            <nav className="navbar">
                <Link to="/" className="logo">
                    <Logo />
                </Link>
                {/* <a href="" className="logo">
                    
                </a> */}
                <div className="nav-links" id="nav-links">
                    <div className="link-background" onMouseEnter={()=>this.onMouseEnter()} onMouseLeave={()=>this.onMouseLeave()}>
                        <a href="" className={"left " + this.state.color}>Menu</a>
                        <img src={menu} alt="Icone Menu"/>
                    </div>
                </div>
            </nav>
        );
    }
}

export default Banner;