export const getToggleFiles = (node) => {
    if (node.isFolder()) {
        let children = []
        node.visit((child) => {
            !child.isFolder() && children.push(child)
        })
        return children
    }
    return [node]
}
