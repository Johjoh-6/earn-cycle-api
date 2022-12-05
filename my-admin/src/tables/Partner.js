import { ListGuesser,
    FieldGuesser } from "@api-platform/admin";

const PartnerList = (props) => (
    <ListGuesser {...props}>
        <FieldGuesser source="name" />
    </ListGuesser>
    );

function Partner(){
    return {
        list: PartnerList,
    }
}
export default Partner;