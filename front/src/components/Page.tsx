import React from "react";
import { Utilisateur } from "../decl";

export type PagesProps = {
    user: Utilisateur | undefined,
}

class LoginPage extends React.Component<PagesProps>{

    render(){
        const { user } = this.props;
        return(
            <div>
                <div> {user?.id} {""}</div>
                <div> {user?.email} </div>
            </div>
        );
    }

}

export default LoginPage;