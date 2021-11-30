import React from "react";
import { User } from "../decl";

export type PagesProps = {
    user: User | undefined,
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