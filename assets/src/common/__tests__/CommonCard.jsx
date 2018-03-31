import { render, shallow } from 'enzyme';

import CommonCard from '../CommonCard';

describe('<CommonCard />', () => {
  let title;
  let type;
  let description;
  let buttonLabel;
  let link;
  let isDisabled;

  beforeEach(() => {
    title = 'Hello world';
    type = 'module';
    description = '**Lorem lipsum** dolor sit amed';
    buttonLabel = 'Watch';
    link = 'https://test.com/test';
    isDisabled = false;
  });

  it('should render properly', () => {
    const wrapper = render(<CommonCard
      title={title}
      type={type}
      description={description}
      buttonLabel={buttonLabel}
      link={link}
      isDisabled={isDisabled}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render disabled properly', () => {
    const wrapper = render(<CommonCard
      title={title}
      type={type}
      description={description}
      buttonLabel={buttonLabel}
      isDisabled={true} />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render empty block properly', () => {
    const wrapper = render(<CommonCard/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should use <div> as wrapper when link is empty', () => {
    const wrapper = shallow(<CommonCard link={null} />);

    expect(wrapper.find('div').length).toBe(2);
  });

  it('should use <a> as wrapper when link is not empty', () => {
    const wrapper = shallow(<CommonCard link={link}/>);

    expect(wrapper.find('a').length).toBe(1);
  });

  it('should set className `inactive` when isDisabled is true', () => {
    const wrapper = shallow(<CommonCard link={link} isDisabled={true}/>);

    expect(wrapper.find('a').hasClass('inactive')).toBeTruthy();
  });

  it('should not have className `clickable` when isDisabled is true', () => {
    const wrapper = shallow(<CommonCard link={link} isDisabled={true}/>);

    expect(wrapper.find('a').hasClass('clickable')).toBeFalsy();
  });

  it('should set className `clickable` when link is not empty', () => {
    const wrapper = shallow(<CommonCard link={link}/>);

    expect(wrapper.find('a').hasClass('clickable')).toBeTruthy();
  });
});
