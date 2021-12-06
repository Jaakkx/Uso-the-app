import React from "react";

export type ButtonProps = {
  };

class Button extends React.Component<ButtonProps>{

    state = {
        hovered:false,
        oldColor:0,
        color:"jaune",
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

    onEnter = () => {
        this.setState({ hovered: true });
        this.color();
    }

    onLeave = () => {
        this.setState({ hovered: false});
        this.color();
    }

    render() {
        return(
            <a className={"spotifyConnexion connect " + this.state.color} onMouseEnter={() => this.onEnter()} onMouseLeave={() => this.onLeave()} href={process.env.REACT_APP_BASE_URL + "/oauth"}>
                <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.668 7C12.802 7 9.66797 3.86599 9.66797 4.17233e-07" stroke="black" stroke-width="0.8"/>
                    <path d="M9.66797 14C9.66797 10.134 12.802 7 16.668 7" stroke="black" stroke-width="0.8"/>
                    <path d="M16.6667 7L0 7" stroke="black" stroke-width="0.8"/>
                </svg>

                Connexion à <span> Spotify</span>
            </a>
        );
    }

}

export default Button;