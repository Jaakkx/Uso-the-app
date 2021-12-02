import React from "react";
import "../css/style.css";

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
                console.log(colorValue);
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
                <div className="logo">
                    <a href="">USO</a>
                </div>
                <div className="nav-links" id="nav-links">
                    <ul>
                        <li>
                            <div className="link-background" onMouseEnter={()=>this.onMouseEnter()} onMouseLeave={()=>this.onMouseLeave()}>
                                <a href="" className={"left " + this.state.color}>Menu</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        );
    }
}

export default Banner;