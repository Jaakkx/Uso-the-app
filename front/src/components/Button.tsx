import React from "react";

export type ButtonProps = {
  };

class Button extends React.Component<ButtonProps>{

    render() {
        return(
            <a target="_blank" href="https://accounts.spotify.com/authorize?client_id=29ced1155da2459f8e661f5beac00a74&response_type=code&redirect_uri=http://uso-api.jael-beining.fr/&scope=user-read-private">Le bouton</a>
        );
    }

}

export default Button;